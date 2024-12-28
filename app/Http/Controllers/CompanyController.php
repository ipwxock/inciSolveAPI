<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Company::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:50|min:5',
            'description' => 'max:255|min:5',
        ]);

        if (!$validatedData) {
            return response()->json(['message' => 'Validation failed'], 400);
        }

        $company = Company::create($validatedData);

        return response()->json($company, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return response()->json($company, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:50|min:5',
            'description' => 'max:255|min:5',
        ]);

        if (!$validatedData) {
            return response()->json(['message' => 'Validation failed'], 400);
        }

        $company->update($validatedData);

        return response()->json($company, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return response()->json(null, 204);
    }


    public function getAllMyEmployees(User $user)
    {
        $employee = Employee::where('auth_id', $user->id)->first();
        $company = Company::where('id', $employee->company_id)->first();
        // Verifica si la relación está cargada y si hay empleados.
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


}
