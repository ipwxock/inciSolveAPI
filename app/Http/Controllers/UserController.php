<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Http\Request;

class UserController
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    public function store(User $user)
    {
        // Validar los datos generales de usuario
        $validatedData = request()->validate([
            'dni' => 'required|unique:users|max:9|regex:/^[0-9]{8}[A-Za-z]$/',
            'first_name' => 'required|max:25|min:2',
            'last_name' => 'required|max:25|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:25',
            'role' => 'required|in:Admin,Manager,Empleado,Cliente',
            'phone_number' => 'nullable|required_if:role,Cliente|regex:/^[0-9]{9}$/', // Para Cliente
            'address' => 'nullable|required_if:role,Cliente|max:255', // Para Cliente
            'company_id' => 'nullable|required_if:role,Empleado|exists:companies,id', // Para Empleado
        ]);

        // Crear el usuario principal
        $user = User::create([
            'dni' => $validatedData['dni'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        // Crear la entidad relacionada según el rol
        if ($validatedData['role'] === 'Empleado') {
            Employee::create([
                'auth_id' => $user->id, // Relación con User
                'company_id' => $validatedData['company_id'], // Campo adicional
            ]);
        } elseif ($validatedData['role'] === 'Manager') {
            Employee::create([
                'auth_id' => $user->id, // Relación con User
                'company_id' => $validatedData['company_id'], // Campo adicional
            ]);
        
        } elseif ($validatedData['role'] === 'Cliente') {
            Customer::create([
                'auth_id' => $user->id, // Relación con User
                'phone_number' => $validatedData['phone_number'], // Campo adicional
                'address' => $validatedData['address'], // Campo adicional
            ]);
        }

        return response()->json(['user' => $user->makeHidden(['password'])], 201);
    }





    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        if ($user->role === 'Empleado' || $user->role === 'Manager') {
            $user->employee = Employee::where('auth_id', $user->id)->first();
        } 
        
        if ($user->role === 'Cliente') {
            $user->customer = Customer::where('auth_id', $user->id)->first();
        }
    
        return response()->json($user->makeHidden(['password']), 200);
    }




    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $userToEdit = User::findOrFail($user->id);

        if (!$userToEdit || !$userToEdit instanceof User) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'dni' => 'unique:users|max:9|min:9|pattern:/[0-9]{8}[A-Za-z]{1}/',
            'first_name' => 'max:25|min:2',
            'last_name' => 'max:25|min:2',
            'email' => 'email',
            'role' => 'in:Admin,Manager,Empleado,Cliente',
            'phone_number' => 'nullable|regex:/^[0-9]{9}$/', // Para Cliente
            'address' => 'nullable|max:255', // Para Cliente
            'company_id' => 'nullable|exists:companies,id', // Para Empleado/manager
        ]);

        if (!$validatedData) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $userToEdit->update($validatedData);

        if ($userToEdit->role === 'Empleado' || $userToEdit->role === 'Manager')
        {
            $employee = Employee::where('auth_id', $userToEdit->id)->first();
            if (isset($validatedData['company_id'])) {
                $employee->update([
                    'company_id' => $validatedData['company_id'],
                ]);
            }
            

            $userToEdit->employee = $employee;

        } elseif ($userToEdit->role === 'Cliente') {
            $customer = Customer::where('auth_id', $userToEdit->id)->first();

            if (isset($validatedData['phone_number'])){
                $customer->update([
                    'phone_number' => $validatedData['phone_number'],
                ]);
            }

            if (isset($validatedData['address'])) {
                $customer->update([
                    'address' => $validatedData['address'],
                ]);
            }

            $userToEdit->customer = $customer;
        }

        return response()->json($userToEdit->makeHidden(['password']), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $userToDelete = User::findOrfail($user->id);

        if (!$userToDelete || !$userToDelete instanceof User) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted'], 204);
    }
}
