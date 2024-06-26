<?php

namespace App\Http\Controllers\API\V1;

use DateTime;
use League\Csv\Reader;
use App\Models\Employee;
use App\Traits\DateFormat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{

    use DateFormat;

    // Retrieve a list of all employees
    public function index()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    // Retrieve a specific employee by ID
    public function show($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return response()->json($employee);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
    }

    // Delete a specific employee by ID
    public function destroy($id)
    {
        $employee = Employee::find($id);
        if (is_null($employee)) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
        $employee->delete();
        return response()->json(null, 204);
    }

    public function store(Request $request)
    {
        // Check if a 'csv_file' has been uploaded
        if ($request->hasFile('csv_file')) {
            // Validate the file to ensure it is a CSV or TXT file
            $validator = Validator::make($request->all(), [
                'csv_file' => 'required|mimes:csv,txt',
            ]);

            // If validation fails, return an error response
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Get the uploaded CSV file from the request
            $file = $request->file('csv_file');

            // Store the file in Laravel storage under a specific name
            $filePath = $file->storeAs('csv_imports', $file->getClientOriginalName());

            // Process the CSV file
            try {
                // Create a CSV reader from the stored file
                $csv = Reader::createFromPath(storage_path('app/' . $filePath), 'r');
                $csv->setHeaderOffset(0); // Set the header offset if the first record is header

                // Define the batch size for batch processing
                $chunkSize = 100;
                $chunks = array_chunk(iterator_to_array($csv->getRecords()), $chunkSize);

                // Iterate over each batch of CSV records
                foreach ($chunks as $chunk) {
                    $employees = []; // Array to store employee records

                    // Iterate over each record in the batch
                    foreach ($chunk as $record) {
                        // Convert dates to 'Y-m-d' format
                        $dateOfBirth = $this->format_date($record['Date of Birth']);
                        $dateOfJoining = $this->format_date($record['Date of Joining']);
                        $timeOfBirth = $this->format_time($record['Time of Birth']);
                        // Build an array of employee data from the CSV fields
                        $employees[] = [
                            'employee_id' => $record['Emp ID'],
                            'username' => $record['User Name'],
                            'name_prefix' => $record['Name Prefix'],
                            'first_name' => $record['First Name'],
                            'middle_initial' => $record['Middle Initial'],
                            'last_name' => $record['Last Name'],
                            'gender' => $record['Gender'],
                            'email' => $record['E Mail'],
                            'date_of_birth' => $dateOfBirth,
                            'time_of_birth' => $timeOfBirth,
                            'age' => $record['Age in Yrs.'],
                            'date_of_joining' => $dateOfJoining,
                            'years_in_company' => $record['Age in Company (Years)'],
                            'phone_number' => $record['Phone No. '],
                            'place_name' => $record['Place Name'],
                            'county' => $record['County'],
                            'city' => $record['City'],
                            'zip' => $record['Zip'],
                            'region' => $record['Region'],
                        ];
                    }

                    // Insert employees into the database using Eloquent ORM
                    Employee::insert($employees);
                }

                // Return a response indicating employees were imported successfully
                return response()->json(['message' => 'Employees imported successfully'], 201);
            } catch (\Exception $e) {
                // Handle any exceptions that occur during CSV file processing
                return response()->json(['error' => 'Error processing CSV file: ' . $e->getMessage()], 500);
            }
        } else {
            // Handle case where no CSV file was provided in the request
            return response()->json(['error' => 'No CSV file provided in the request'], 400);
        }
    }
}
