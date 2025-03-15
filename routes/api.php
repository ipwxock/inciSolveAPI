<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\IssueController;

/**
 * Ruta de fallback en caso de que no se encuentre una ruta definida.
 *
 * @return \Illuminate\Http\JsonResponse
 */
Route::fallback(function () {
    return response()->json([
        'message' => 'Ruta no encontrada',
        'success' => false,
        'error' => '404 Not Found',
    ], 404);
});

/**
 * Endpoint para iniciar sesión.
 *
 * @route POST /login
 * @uses AuthController::login()
 */
Route::post('/login', [AuthController::class, 'login']);

/**
 * Grupo de rutas protegidas por autenticación (Sanctum).
 */
Route::middleware('auth:sanctum')->group(function () {

    /**
     * Obtiene la información del usuario autenticado.
     *
     * @route GET /user
     * @uses AuthController::user()
     */
    Route::get('/user', [AuthController::class, 'user']);

    /**
     * Cierra sesión del usuario autenticado.
     *
     * @route POST /logout
     * @uses AuthController::logout()
     */
    Route::post('/logout', [AuthController::class, 'logout']);

    /**
     * Verifica el rol del usuario autenticado.
     *
     * @route GET /role-check
     * @uses AuthController::roleCheck()
     */
    Route::get('/role-check', [AuthController::class, 'roleCheck']);
});

/**
 * Grupo de rutas para la gestión de usuarios.
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);  // Listar usuarios
    Route::post('/users', [UserController::class, 'store']); // Crear usuario
    Route::get('/users/{user}', [UserController::class, 'show']); // Ver un usuario
    Route::put('/users/{user}', [UserController::class, 'update']); // Actualizar usuario
    Route::delete('/users/{user}', [UserController::class, 'destroy']); // Eliminar usuario
});

/**
 * Grupo de rutas para la gestión de empresas.
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/companies', [CompanyController::class, 'index']);
    Route::post('/companies', [CompanyController::class, 'store']);
    Route::get('/companies/{company}', [CompanyController::class, 'show']);
    Route::put('/companies/{company}', [CompanyController::class, 'update']);
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy']);

    /**
     * Obtiene una versión simplificada de la empresa.
     *
     * @route GET /companies/{company}/get-company-simple
     * @uses CompanyController::viewSimple()
     */
    Route::get('/companies/{company}/get-company-simple', [CompanyController::class, 'viewSimple'])
        ->whereNumber('company');
});

/**
 * Grupo de rutas para la gestión de empleados.
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/employees/{employee}', [EmployeeController::class, 'show']);
    Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
    Route::get('/employees/{employee}/get-employee-detail', [EmployeeController::class, 'getEmployeeDetail'])
        ->whereNumber('employee');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);
});

/**
 * Grupo de rutas para la gestión de clientes.
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{customer}', [CustomerController::class, 'show']);
    Route::put('/customers/{customer}', [CustomerController::class, 'update']);
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);
    Route::get('/customers/{customer}/get-customer-detail', [CustomerController::class, 'getCustomerDetail'])
        ->whereNumber('customer');
});

/**
 * Grupo de rutas para la gestión de pólizas de seguro.
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/insurances', [InsuranceController::class, 'index']);
    Route::post('/insurances', [InsuranceController::class, 'store']);
    Route::get('/insurances/{insurance}', [InsuranceController::class, 'show']);
    Route::put('/insurances/{insurance}', [InsuranceController::class, 'update']);
    Route::delete('/insurances/{insurance}', [InsuranceController::class, 'destroy']);
    Route::get('/insurances/{insurance}/get-insurance-detail', [InsuranceController::class, 'getInsuranceDetail'])
        ->whereNumber('insurance');
});

/**
 * Grupo de rutas para la gestión de incidencias.
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/issues', [IssueController::class, 'index']);
    Route::post('/issues', [IssueController::class, 'store']);
    Route::get('/issues/{issue}', [IssueController::class, 'show']);
    Route::put('/issues/{issue}', [IssueController::class, 'update']);
    Route::delete('/issues/{issue}', [IssueController::class, 'destroy']);
    Route::get('/issues/{issue}/get-issue-detail', [IssueController::class, 'getIssueDetail'])
        ->whereNumber('issue');
});

/**
 * Rutas fuera del estándar REST para obtener datos adicionales.
 */
Route::middleware(['auth:sanctum'])->group(function () {
    /**
     * Obtiene todos los empleados relacionados con el usuario autenticado.
     * @route GET /get-my-employees
     * @uses EmployeeController::getAllMyEmployees()
     */
    Route::get('/get-my-employees', [EmployeeController::class, 'getAllMyEmployees']);

    /**
     * Obtiene todos los clientes relacionados con el usuario autenticado.
     * @route GET /get-my-customers
     * @uses CustomerController::getAllMyCustomers()
     */
    Route::get('/get-my-customers', [CustomerController::class, 'getAllMyCustomers']);

    /**
     * Obtiene todas las pólizas de seguro relacionadas con el usuario autenticado.
     * @route GET /get-my-insurances
     * @uses InsuranceController::getAllMyInsurances()
     */
    Route::get('/get-my-insurances', [InsuranceController::class, 'getAllMyInsurances']);

    /**
     * Obtiene todas las incidencias relacionadas con el usuario autenticado.
     * @route GET /get-my-issues
     * @uses IssueController::getAllMyIssues()
     */
    Route::get('/get-my-issues', [IssueController::class, 'getAllMyIssues']);

    /**
     * Obtiene el ID de la empresa a la que pertenece el usuario autenticado.
     * @route GET /get-my-company-id
     * @uses CompanyController::getMyCompanyId()
     */
    Route::get('/get-my-company-id', [CompanyController::class, 'getMyCompanyId']);
});


