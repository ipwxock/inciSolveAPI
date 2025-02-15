<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['admin', 'manager', 'employee', 'customer'])->get();

        // Ocultar la contraseña de cada usuario
        $users->makeHidden(['password']);

        return response()->json($users, 200);
    }

    public function store(Request $request)
    {
        // Validar todos los datos de entrada
        $validatedData = $this->validateUserData($request);

        // Crear el usuario principal
        $user = User::create([
            'dni' => $validatedData['dni'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['dni']),
            'role' => $validatedData['role'],
        ]);
        
        // Crear la entidad específica según el rol
        $this->createRelatedEntity($user, $validatedData);

        // Ocultar campos sensibles como "password" antes de responder
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

        $validatedData = $this->validateUserData($request, $userToEdit);

        if (!$validatedData) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $userToEdit->update($validatedData);

        $this->updateRelatedEntity($userToEdit, $validatedData);
        
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

    private function createRelatedEntity(User $user, array $data)
    {
        $creator = Auth::user();

        if (!$creator || $creator->role === 'Cliente') {
            return response()->json(['error' => 'Usted no está autorizado.'], 403);
        }

        try{
            switch ($data['role']) {
                case 'Empleado':
                    $companyId = $creator->role === 'Admin' ? $data['company_id'] : $creator->employee->company_id;

                    if (!$companyId) {
                        return response()->json(['error' => 'No se ha especificado una compañía.'], 400);
                    }

                    $employee = Employee::create([
                        'auth_id' => $user->id,
                        'company_id' => $companyId,
                    ]);
                    $user->employee()->associate($employee);
                    break;
                case 'Manager':
                    $companyId = $data['company_id'];
                    if (!$companyId) {
                        return response()->json(['error' => 'No se ha especificado una compañía.'], 400);
                    }
                    $employee = Employee::create([
                        'auth_id' => $user->id,
                        'company_id' => $companyId,
                    ]);
                    $user->employee()->associate($employee);
                    break;
    
                case 'Cliente':
                    $customer = Customer::create([
                        'auth_id' => $user->id,
                        'phone_number' => $data['phone_number'],
                        'address' => $data['address'],
                    ]);
                    $user->customer()->associate($customer);
                    break;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function updateRelatedEntity(User $user, array $data)
    {
        try{
            switch ($data['role']) {
                case 'Empleado':
                case 'Manager':
                    $employee = Employee::where('auth_id', $user->id)->first();
                    $employee->update([
                        'company_id' => $data['company_id'],
                    ]);
                    $user->employee()->associate($employee);
                    break;
    
                case 'Cliente':
                    $customer = Customer::where('auth_id', $user->id)->first();
                    $customer->update([
                        'phone_number' => $data['phone_number'],
                        'address' => $data['address'],
                    ]);
                    $user->customer()->associate($customer);
                    break;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }

    private function validateUserData(Request $request, User $user = null)
{
    return $request->validate([
        'dni' => [
            'max:9',
            'min:9',
            'regex:/^[0-9]{8}[A-Za-z]$/',
            Rule::unique('users', 'dni')->ignore($user?->id),
        ],
        'first_name' => 'required|max:25|min:2',
        'last_name' => 'required|max:25|min:2',
        'email' => [
            'email',
            Rule::unique('users', 'email')->ignore($user?->id),
        ],
        'role' => 'required|in:Admin,Manager,Empleado,Cliente',
        'phone_number' => 'nullable|required_if:role,Cliente|regex:/^[0-9]{9}$/',
        'address' => 'nullable|required_if:role,Cliente|max:255',
        'company_id' => 'nullable',
    ]);
}


}
