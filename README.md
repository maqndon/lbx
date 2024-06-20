# Employee Management REST API

## Setup Instructions

1. Clone the repository:
    ```bash
    git clone https://github.com/maqndon/lbx.git
    cd lbx
    ```

2. Install dependencies:
    ```bash
    composer install
    ```

3. Configure the environment variables in the .env file:

DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=employee_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

4. Run the migrations:
    ```bash
    php artisan migrate
    ```
5. Generate a new Key App
    ```bash
    php artisan key:generate
    ```
6. Serve the application:
    ```bash
    php artisan serve
    ```

## API Endpoints
- POST /api/employee - Import employees from a CSV file.
- GET /api/employee - Get all employees.
- GET /api/employee/{id} - Get a specific employee by ID.
- DELETE /api/employee/{id} - Delete a specific employee by ID.

## Notes
- The API expects the CSV file to have headers as specified in the provided import.csv file.
- For large CSV files, the import process is designed to be reliable and efficient.
- Security, domain structures, and performance optimizations are documented in the code comments and this README.


### Additional Notes
If this were a real-world task, additional considerations might include:
- Implementing authentication and authorization.
- Adding unit and integration tests.
- Handling potential CSV parsing errors more gracefully.
- Implementing pagination for the `GET /api/employee` endpoint.
- Optimizing the batch import process to handle extremely large files efficiently, possibly using queue jobs.
- Detailed API documentation using tools like Swagger.

Package your results and submit them as instructed in the task description.
