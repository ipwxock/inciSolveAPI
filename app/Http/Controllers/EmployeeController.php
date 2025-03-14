<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Http\Policies\EmployeePolicy;
use App\Models\Insurance;
use App\Models\Issue;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class EmployeeController
{

    /**
     * Devuelve una lista de todos los empleados si el usuario está autenticado y autorizado.
     *
     * @return \Illuminate\Http\JsonResponse Lista de empleados o un mensaje de error si no está autorizado.
     */
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!EmployeePolicy::viewAll($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $employees = Employee::all();

        // Formatear la respuesta para incluir la relación con el usuario
        $response = $employees->map(function ($employee) {
            $user = User::find($employee->auth_id)->makeHidden(['password']);
            return [
                'employee' => $employee,
                'user' => $user,
            ];
        });

         return response()->json($response, 200);

        return response()->json($response, 200);
    }


    /**
     * Muestra los detalles de un empleado específico si el usuario tiene permisos adecuados.
     *
     * @param Employee $employee Empleado a mostrar.
     * @return \Illuminate\Http\JsonResponse Detalles del empleado y su información de usuario o un mensaje de error si no está autorizado.
     */
    public function show(Employee $employee)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!EmployeePolicy::view($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $employeeUser = User::find($employee->auth_id)->makeHidden(['password']);

        return response()->json([
            'employee' => $employee,
            'user' => $employeeUser,
        ], 200);
    }


    /**
     * Elimina un empleado si el usuario tiene permisos y no tiene seguros asociados.
     *
     * @param Employee $employee Empleado a eliminar.
     * @return \Illuminate\Http\JsonResponse Mensaje de confirmación o error si no está autorizado o el empleado tiene seguros asociados.
     */
    public function destroy(Employee $employee)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!EmployeePolicy::delete($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $insurances = Insurance::where('employee_id', $employee->id)->get();

        if (!$insurances->isEmpty()) {
            return response()->json(['message' => 'No se puede eliminar un empleado con seguros asociados.'], 400);
        }

        $employee->delete();
        return response()->json("Empleado despedido correctamente", 204);
    }

    /**
     * Obtiene todos los empleados de la empresa del usuario autenticado si tiene permisos adecuados.
     *
     * @return \Illuminate\Http\JsonResponse Lista de empleados con sus datos de usuario o un mensaje de error si no está autorizado.
     */
    public function getAllMyEmployees()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!EmployeePolicy::viewMyEmployees($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $employee = Employee::where('auth_id', $user->id)->first();

        if (!$employee) {
            return response()->json(['message' => 'No se encontró un registro de empleado para este usuario.'], 400);
        }

        $company = Company::where('id', $employee->company_id)->first();

        if (!$company) {
            return response()->json(['message' => 'No se encontró una empresa asociada a este empleado.'], 400);
        }

        $employees = $company->employees;

        if ($employees->isEmpty()) {
            return response()->json(['message' => 'Esta empresa no tiene empleados.'], 404);
        }

        // Prepara un arreglo con los datos organizados como empleado y usuario.
        $result = $employees->map(function ($employee) {
            $user = User::find($employee->auth_id)->makeHidden(['password']);
            return [
                'employee' => $employee,
                'user' => $user,
            ];
        });

        // Devuelve la respuesta JSON con los datos normalizados.
        return response()->json($result, 200);
    }

    /**
     * Muestra los detalles completos de un empleado, incluyendo sus seguros e incidencias, si el usuario tiene permisos adecuados.
     *
     * @param Employee $employee Empleado a mostrar.
     * @return \Illuminate\Http\JsonResponse Detalles del empleado, sus seguros e incidencias o un mensaje de error si no está autorizado.
     */
    public function getEmployeeDetail(Employee $employee)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!EmployeePolicy::view($user) || ($user->role==='Empleado' && $user->id !== $employee->auth_id)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $employeeUser = User::where('id', $employee->auth_id)->first();

        $insurances = Insurance::where('employee_id', $employee->id)->get();

        $issues = collect();

        foreach ($insurances as $insurance) {
            $iss = Issue::where('insurance_id', $insurance->id)->get();
            $issues = $issues->merge($iss);
        }

        $response = [
            'employee' => $employee,
            'user' => $employeeUser,
            'insurances' => $insurances,
            'issues' => $issues,
        ];

        return response()->json($response, 200);
    }

    /**
     * Complementa los datos del empleado con información del usuario asociado.
     *
     * @param User $user Usuario asociado al empleado.
     * @param Employee $employee Empleado cuyos datos se complementarán.
     * @return Employee|null Devuelve el empleado con los datos complementados o null si hay datos faltantes.
     */
    public function composeEmployeeData(User $user, Employee $employee){

        if (!isEmpty($user) && !isEmpty($employee)){
            $employee['dni'] = $user['dni'];
            $employee['last_name'] = $user['last_name'];
            $employee['email'] = $user['email'];
            $employee['role'] = $user['role'];
        } else {
            return null;
        }

        return $employee;
    }
}
