<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\IssueController;
use App\Models\Insurance;

//Users
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::put('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);


//Companies
Route::get('/companies', [CompanyController::class, 'index']);
Route::post('/companies', [CompanyController::class, 'store']);
Route::get('/companies/{company}', [CompanyController::class, 'show']);
Route::put('/companies/{company}', [CompanyController::class, 'update']);
Route::delete('/companies/{company}', [CompanyController::class, 'destroy']);
Route::get('/companies/{company}/employees', [CompanyController::class, 'getAllMyEmployees']);

//Employees
Route::get('/employees', [EmployeeController::class, 'index']);
// Route::post('/employees', [EmployeeController::class, 'store']);
Route::get('/employees/{employee}', [EmployeeController::class, 'show']);
Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);

Route::get('/employees/{employee}/insurances', [EmployeeController::class, 'getAllMyInsurances']);
Route::get('/employees/{employee}/issues', [EmployeeController::class, 'getAllMyIssues']);

//Customers
Route::get('/customers', [CustomerController::class, 'index']);
// Route::post('/customers', [CustomerController::class, 'store']);
Route::get('/customers/{customer}', [CustomerController::class, 'show']);
Route::put('/customers/{customer}', [CustomerController::class, 'update']);
Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);

Route::get('/customers/{customer}/insurances', [CustomerController::class, 'getAllMyInsurances']);
Route::get('/customers/{customer}/issues', [CustomerController::class, 'getAllMyIssues']);

//Insurances
Route::get('/insurances', [InsuranceController::class, 'index']);
Route::post('/insurances', [InsuranceController::class, 'store']);
Route::get('/insurances/{insurance}', [InsuranceController::class, 'show']);
Route::put('/insurances/{insurance}', [InsuranceController::class, 'update']);
Route::delete('/insurances/{insurance}', [InsuranceController::class, 'destroy']);

//Issues
Route::get('/issues', [IssueController::class, 'index']);
Route::post('/issues', [IssueController::class, 'store']);
Route::get('/issues/{issue}', [IssueController::class, 'show']);
Route::put('/issues/{issue}', [IssueController::class, 'update']);
Route::delete('/issues/{issue}', [IssueController::class, 'destroy']);
