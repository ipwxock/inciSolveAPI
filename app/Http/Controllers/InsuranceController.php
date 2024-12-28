<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Insurance;
use App\Models\User;
use Illuminate\Http\Request;

class InsuranceController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Insurance::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de datos iniciales
        $validatedData = $request->validate([
            'subject_type' => 'required|in:Vida,Robo,Defunción,Accidente,Incendios,Asistencia_carretera,Salud,Hogar,Auto,Viaje,Mascotas,Otros',
            'description' => 'required|max:255|min:5',
            'customer_id' => 'nullable|exists:customers,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        // Verificar si existe el cliente (si se proporciona customer_id)
        if (isset($validatedData['customer_id'])) {
            $customer = Customer::find($validatedData['customer_id']);
            if (!$customer) {
                return response()->json(['message' => 'Customer not found'], 404);
            }
        } else {
            // Crear un nuevo usuario si no existe customer_id
            $userData = $this->validateUserData($request); // Método separado para validar usuario
            $user = User::create($userData);

            // Asociar el usuario a un nuevo cliente
            $customer = Customer::create([
                'auth_id' => $user->id,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ]);

            $validatedData['customer_id'] = $customer->id;
        }

        // Crear el seguro usando los datos validados
        $insurance = Insurance::create($validatedData);

        return response()->json($insurance, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Insurance $insurance)
    {
        return response()->json($insurance, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insurance $insurance)
    {
        $validatedData = $request->validate([
            'subject_type' => 'in:Vida,Robo,Defunción,Accidente,Incendios,Asistencia_carretera,Salud,Hogar,Auto,Viaje,Mascotas,Otros',
            'description' => 'max:255|min:5',
            'customer_id' => 'exists:customers,id',
            'employee_id' => 'exists:employees,id',
        ]);

        if ($request->filled('subject_type') && $request->subject_type !== $insurance->subject_type) {
            return response()->json(['message' => 'No se puede cambiar el tipo de póliza. Sólo las condiciones.'], 400);
        }

        $insurance->update($validatedData);

        return response()->json($insurance, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        $insurance->delete();

        return response()->json(null, 204);
    }


    private function validateUserData(Request $request)
    {
        return $request->validate([
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

    }
}