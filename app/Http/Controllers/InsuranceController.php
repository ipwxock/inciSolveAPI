<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
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
        $validatedData = $request->validate([
            'subject_type' => 'required|in:Vida,Robo,Defunción,Accidente,Incendios,Asistencia_carretera,Salud,Hogar,Auto,Viaje,Mascotas,Otros',
            'description' => 'required|max:255|min:5',
            'customer_id' => 'required|exists:customers,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        if (!$validatedData) {
            return response()->json(['message' => 'Validation failed'], 400);
        }

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
}
