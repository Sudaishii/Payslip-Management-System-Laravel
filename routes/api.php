<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;



use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\HumanResourceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStatusController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\DailyTimeRecordController;
use App\Http\Controllers\PayslipStatusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthenticationController::class, 'logout']);


    Route::middleware('role:1,2')->group(function () {
        // Human Resource Page (full access)
          Route::post('/add-employee', [HumanResourceController::class, 'addEmployee']);
          Route::put('/update-employee/{id}', [HumanResourceController::class, 'editEmployee']);
          Route::delete('/delete-employee/{id}', [HumanResourceController::class, 'deleteEmployee']);
          Route::get('/get-employees', [HumanResourceController::class, 'getEmployees']);
          Route::get('/get-employee/{id}', [HumanResourceController::class, 'getEmployeeById']);

        // Daily Time Records Page
        Route::post('/import-daily-time-records', [DailyTimeRecordController::class, 'importCsv']);
        Route::post('/add-daily-time-record', [DailyTimeRecordController::class, 'addRecord']);
        Route::get('/get-daily-time-records', [DailyTimeRecordController::class, 'getRecords']);
        Route::get('/get-daily-time-record/{id}', [DailyTimeRecordController::class, 'getRecordById']);
       

        // Report Page
        Route::post('/generate-report', [DailyTimeRecordController::class, 'generateReport']);
        Route::delete('/delete-report/{reportId}', [ReportController::class, 'deleteReport']);
        Route::get('/get-reports', [ReportController::class, 'getAllPayslipReports']);
        Route::get('/get-report/{id}', [ReportController::class, 'getPayslipReport']);

        // Payslip Statuses Routes
        Route::get('/get-paylsip-status', [PayslipStatusController::class, 'getPayslipStatuses']);
        Route::get('/get-paylsip-status/{id}', [PayslipStatusController::class, 'getPayslipStatusById']);
        Route::post('/add-paylsip-status', [PayslipStatusController::class, 'addPayslipStatus']);
        Route::put('/edit-paylsip-status/{id}', [PayslipStatusController::class, 'editPayslipStatus']);
        Route::delete('/delete-paylsip-status/{id}', [PayslipStatusController::class, 'deletePayslipStatus']);
        // Route::get('/payslip-reports/employee/{employeeId}', [ReportController::class, 'getPayslipReportsByEmployeeId']);

    });

   
    Route::middleware('role:1')->group(function () {
      

        // Users Page
        Route::get('/get-users', [UserController::class, 'getUsers']);
        Route::get('/get-user/{id}', [UserController::class, 'getUserById']);
        Route::post('/add-user', [UserController::class, 'addUser']);
        Route::put('/edit-user/{id}', [UserController::class, 'editUser']);
        Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);

        // User Status Page
        Route::get('/user-statuses', [UserStatusController::class, 'getUserStatus']);
        Route::get('/user-status/{id}', [UserStatusController::class, 'getUserStatusById']);
        Route::post('/add-user-status', [UserStatusController::class, 'addUserStatus']);
        Route::put('/edit-user-status/{id}', [UserStatusController::class, 'editUserStatus']);
        Route::delete('/delete-user-status/{id}', [UserStatusController::class, 'deleteUserStatus']);

        // Roles Page
        Route::get('/get-roles', [RolesController::class, 'getRoles']);
        Route::post('/add-role', [RolesController::class, 'addRole']);
        Route::put('/edit-role/{id}', [RolesController::class, 'editRole']);
        Route::delete('/delete-role/{id}', [RolesController::class, 'deleteRole']);
    });

    
   
});
