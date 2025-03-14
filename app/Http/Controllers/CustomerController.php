<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Http\Policies\CustomerPolicy;
use App\Models\Insurance;
use App\Models\Issue;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerController
{

    /**
     * Devuelve una lista de todos los clientes si el usuario está autenticado y autorizado.
     *
     * @return \Illuminate\Http\JsonResponse Lista de clientes con sus datos de usuario o un mensaje de error si no está autorizado.
     */
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!CustomerPolicy::viewAllCustomers($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $customers = Customer::all();

        // Formatear la respuesta para incluir la relación con el usuario
        $response = $customers->map(function ($customer) {
            $user = User::find($customer->auth_id)->makeHidden(['password']);
            return [
                'customer' => $customer,
                'user' => $user,
            ];
        });

         return response()->json($response, 200);

    }


    /**
     * Muestra los detalles de un cliente específico si el usuario tiene permisos adecuados.
     *
     * @param Customer $customer Cliente a mostrar.
     * @return \Illuminate\Http\JsonResponse Detalles del cliente y su información de usuario o un mensaje de error si no está autorizado.
     */
    public function show(Customer $customer)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!CustomerPolicy::view($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $customerUser = User::findOrFail($customer->auth_id);

        if (!$customerUser) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['customer'=>$customer, 'user'=>$customerUser->makeHidden(['password'])], 200);
    }

    /**
     * Obtiene todos los clientes asociados a las pólizas vendidas por el empleado autenticado.
     *
     * @return \Illuminate\Http\JsonResponse Lista de clientes con sus datos de usuario o un mensaje de error si no está autorizado.
     */
    public function getAllMyCustomers()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $employeeUser = Auth::user();

        if (!CustomerPolicy::viewMyCustomers($employeeUser)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $employee = Employee::where('auth_id', $employeeUser->id)->first();

        // Obtener las pólizas (insurances) vendidas por el empleado
        $insuranceCustomerIds = Insurance::where('employee_id', $employee->id)->pluck('customer_id');

        // Obtener los clientes (customers) asociados a esas pólizas
        $customers = Customer::whereIn('id', $insuranceCustomerIds)->get();

        // Formatear la respuesta para incluir la relación con el usuario
        $response = $customers->map(function ($customer) {
            $user = User::find($customer->auth_id)->makeHidden(['password']);
            return [
                'customer' => $customer,
                'user' => $user,
            ];
        });
        return response()->json($response, 200);
    }

    /**
     * Muestra los detalles completos de un cliente, incluyendo sus seguros e incidencias, si el usuario tiene permisos adecuados.
     *
     * @param Customer $customer Cliente a mostrar.
     * @return \Illuminate\Http\JsonResponse Detalles del cliente, sus seguros e incidencias o un mensaje de error si no está autorizado.
     */
    public function getCustomerDetail(Customer $customer)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $employeeUser = Auth::user();

        if (!CustomerPolicy::view($employeeUser)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        // Obtener al empleado directamente usando la relación
        $employee = $employeeUser->employee;

        if (!$employee) {
            return response()->json(['message' => 'Empleado no encontrado'], 403);
        }

        // Comprobar que existe algun Insurance asociado al empleado y al cliente
        $insuranceExists = Insurance::where('employee_id', $employee->id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$insuranceExists) {
            return response()->json(['message' => 'No tienes permiso. No es cliente tuyo.'], 403);
        }

        // Usar relaciones para obtener los seguros del cliente asociados al empleado
        $insurances = Insurance::where('employee_id', $employee->id)
            ->where('customer_id', $customer->id)
            ->get();

        // Obtener las incidencias asociadas a esas pólizas
        $issues = Issue::whereIn('insurance_id', $insurances->pluck('id'))->get();

        // Formatear la respuesta
        $response = [
            'customer' => $customer,
            'user' => $customer->user->makeHidden(['password']), // Asume que la relación `user` está en el modelo Customer
            'insurances' => $insurances,
            'issues' => $issues,
        ];

        return response()->json($response, 200);
    }

}
