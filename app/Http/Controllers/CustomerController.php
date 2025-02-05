<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Insurance;
use App\Models\Issue;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CustomerController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $user = User::findOrFail($customer->auth_id);
      
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['customer'=>$customer, 'user'=>$user->makeHidden(['password'])], 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'dni' => 'unique:users,dni,' . $customer->auth_id . '|max:9|min:9|regex:/[0-9]{8}[A-Za-z]{1}/',
            'first_name' => 'max:25|min:2',
            'last_name' => 'max:25|min:2',
            'email' => 'email|unique:users,email,' . $customer->auth_id,
            'address' => 'max:50|min:5',
            'phone_number' => 'max:15|min:9|pattern:/[0-9]{9,15}/',
        ]);
    
        if (!$validatedData) {
            return response()->json("Datos no válidos.", 400);
        }
    
        // Obtener el usuario relacionado
        $user = User::find($customer->auth_id);
    
        // Actualizar customer si es necesario
        if (isset($validatedData['address'])) {
            $customer->address = $validatedData['address'];
        }

        if (isset($validatedData['phone_number'])) {
            $customer->phone_number = $validatedData['phone_number'];    
        }
    
        // Actualizar propiedades de User si existen en el request
        $userFields = ['dni', 'first_name', 'last_name', 'email'];
        foreach ($userFields as $field) {
            if (isset($validatedData[$field])) {
                $user->$field = $validatedData[$field];
            }
        }
    
        // Guardar los cambios
        $user->save(); // Guardar cambios en la tabla users
        $customer->save(); // Guardar cambios en la tabla employees
    
        return response()->json(['employee' => $customer], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json(['message' => 'Cliente eliminado correctamente.'], 200);
    }

    public function getAllMyInsurances(Customer $customer)
    {
        $insurances = Insurance::where('customer_id', $customer->id)->get();

        return response()->json($insurances, 200);
    }

    
    public function getAllMyIssues(Customer $customer)
    {
        $insurances = Insurance::where('customer_id', $customer->id)->get();

        $issues = collect();

        foreach ($insurances as $insurance) {
            $iss = Issue::where('insurance_id', $insurance->id)->get();
            $issues = $issues->merge($iss);
        }

        return response()->json($issues, 200);
    }

    public function getAllMyCustomers()
    {
        $employeeUser = Auth::user();

        if (!$employeeUser) {
            return response()->json(['message' => 'No estás autenticad@'], 401);
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

    public function getCustomerDetail(Customer $customer)
{
    $employeeUser = Auth::user();

    if (!$employeeUser) {
        return response()->json(['message' => 'No estás autenticad@'], 401);
    }

    // Obtener al empleado directamente usando la relación
    $employee = $employeeUser->employee; // Asume que la relación está definida en el modelo User

    if (!$employee) {
        return response()->json(['message' => 'Empleado no encontrado'], 404);
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
