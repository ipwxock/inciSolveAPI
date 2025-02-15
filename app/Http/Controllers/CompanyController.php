<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();

        if(!$user || $user->role !== 'Admin')
        {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|max:50|min:5',
            'description' => 'max:255|min:5',
            'phone_number' => 'nullable|regex:/^[0-9]{9}$/',
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
    $user = Auth::user();

    if (!$user || ($user->role !== 'Admin' && $user->role !== 'Manager')) {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    if ($user->role === 'Manager') {
        $manager = Employee::where('auth_id', $user->id)->first();
        if (!$manager || $manager->company_id !== $company->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
    }

    $employees = $company->employees()->with('user')->get()->map(function ($employee) {
        return [
            'employee' => $employee,
            'user' => $employee->user
        ];
    });

    $insurances = $company->employees()->with('insurances')->get()->flatMap->insurances;

    $issues = $insurances->flatMap->issues;

    return response()->json([
        'company' => $company,
        'employees' => $employees,
        'insurances' => $insurances,
        'issues' => $issues
    ], 200);
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {

        $user = Auth::user();

        if(!$user || ($user->role!== 'Admin' && $user->role !== 'Manager'))
        {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        if ($user->role == 'Manager'){
            $manager = Employee::where('auth_id', $user->id)->first();
            if ($manager->company_id !== $company->id) {
                return response()->json(['message' => 'No autorizado'], 403);
            }
        }

        $validatedData = $request->validate([
            'name' => 'required|max:50|min:5',
            'description' => 'nullable|max:255|min:5',
            'phone_number' => 'nullable|regex:/^[0-9]{9}$/',
        ]);

        $company->update($validatedData);

        return response()->json($company, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {

        $user = Auth::user();

        if(!$user || $user->role!== 'Admin')
        {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $company->delete();

        return response()->json(null, 204);
    }

    public function getMyCompanyId()
    {
        $user = Auth::user();

        if (!$user)
        {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->role !== 'Manager') {
            return response()->json(['message' => 'No autorizado'], 403);
        } 

        $manager = Employee::where('auth_id', $user->id)->first();

        $company = Company::where('id', $manager->company_id)->first();

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        return response()->json($company->id, 200);
    }

}
