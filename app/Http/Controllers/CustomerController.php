<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Insurance;
use App\Models\Issue;
use Illuminate\Http\Request;
use App\Models\User;

class CustomerController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return response()->json(Customer::all(), 200);
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

        $user->customer = $customer;

        return response()->json($user->makeHidden(['password']), 200);

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
}
