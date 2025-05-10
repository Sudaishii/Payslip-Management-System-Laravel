# PayFuse (Payslip Management System) Laravel-Project

## Feature List:
### 1. Authentication
- Register
- Login
- Logout

### 2. Human Resource Page
- Add Employee
- Get Employees(All)
- Get Employee (ID)
- Edit Employee
- Delete Employee

### 3. User Page
- Add User
- Get Users (All)
- Get User (ID)
- Edit User
- Delete User

### 4. User Status Page
- Get  User Statuses
- Add  User Status
- Edit User Status
- Delete User Status

### 5. Roles Page
- Get Roles
- Add Role
- Edit Role
- Delete Role

### 6. Daily Time Records Page
- Import CSV File (Attendance Record)

### 7. Payslip Report Page
- Create Payslip Report
- Delete Payslip Report
- Get Payslip Reports (All)
- Get PAyslip Report (Id)

### 8. Payslip Report Status Page
- Get Payslip Status (All)
- Get Payslip Status (Id)
- Add Payslip Status
- Edit Payslip Status
- Delete Payslip Status


## Prerequisites

Before setting up the project, ensure you have the following installed:

- [XAMPP](https://www.apachefriends.org/download.html) (includes PHP, MySQL, and Apache)
- [Visual Studio Code](https://code.visualstudio.com/download) (recommended code editor)
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/en/download/) (>= 14.x)
- [Git](https://git-scm.com/downloads)

## Setup Instructions

1. Clone the repository:
   ```
   git clone https://github.com/Sudaishii/Payslip-Management-System-Laravel.git
   cd PMS
   ```

2. Open terminal and install PHP dependencies:
   ```
   composer install
   ```

3. Create a copy of the `.env.example` file and rename it to `.env`:
   ```
   cp .env.example .env
   ```

4. Generate an application key:
   ```
   php artisan key:generate
   ```

5. Configure your database in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run database migrations:
   ```
   php artisan migrate
   ```

7. Start the development server:
    ```
    php artisan serve
    ```

8. Visit `http://localhost:8000` in your browser to see the application.

## Running the Application

1. Start the Laravel development server:
   ```
   php artisan serve
   ```

2. Access the application at `http://localhost:8000`

## Additional Configuration

- To configure other services or features, refer to the Laravel documentation: [https://laravel.com/docs](https://laravel.com/docs)

## Troubleshooting

If you encounter any issues during setup or running the application, please check the Laravel and Vue.js documentation or open an issue in this repository.

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.
