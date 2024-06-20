<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class EmployeeController extends Controller
{
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

    // Store employees from a CSV file
    public function store(Request $request)
    {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Store the uploaded file
        $path = $request->file('file')->store('csv');
        $csv = Reader::createFromPath(storage_path('app/' . $path), 'r');
        $csv->setHeaderOffset(0);

        $chunkSize = 100; // adjust the chunk size based on your needs
        $chunks = array_chunk(iterator_to_array($csv->getRecords()), $chunkSize);

        foreach ($chunks as $chunk) {
            $employees = [];
            foreach ($chunk as $record) {
                $employees[] = [
                    'employee_id' => $record['Employee ID'],
                    'username' => $record['User Name'],
                    'name_prefix' => $record['Name Prefix'],
                    'first_name' => $record['First Name'],
                    'middle_initial' => $record['Middle Initial'],
                    'last_name' => $record['Last Name'],
                    'gender' => $record['Gender'],
                    'email' => $record['E-Mail'],
                    'date_of_birth' => $record['Date of Birth'],
                    'time_of_birth' => $record['Time of Birth'],
                    'age_in_years' => $record['Age in Yrs.'],
                    'date_of_joining' => $record['Date of Joining'],
                    'age_in_company' => $record['Age in Company (Years)'],
                    'phone_number' => $record['Phone No.'],
                    'place_name' => $record['Place Name'],
                    'county' => $record['County'],
                    'city' => $record['City'],
                    'zip' => $record['Zip'],
                    'region' => $record['Region'],
                ];
            }
            Employee::insert($employees);
        }

        return response()->json(['message' => 'Employees imported successfully'], 201);
    }
}