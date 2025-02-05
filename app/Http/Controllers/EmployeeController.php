<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
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
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Employee::all(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'dni' => ['required', 'unique:users', 'max:9', 'min:9', 'regex:/[0-9]{8}[A-Za-z]{1}/'],
            'first_name' => 'required|max:25|min:2',
            'last_name' => 'required|max:25|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:25',
            'company_id' => 'required|exists:companies,id', // Para Empleado/manager
        ]);
    
        if (!$validatedData)
        {
            return response()->json("Datos no válidos.", 400);
        }

        DB::beginTransaction();
    
        try {
            // Crear el usuario
            $newUser = User::create([
                'dni' => $validatedData['dni'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'role' => 'Empleado',
            ]);
    
            // Crear el empleado relacionado
            $newEmployee = $newUser->employee()->create([
                'company_id' => $validatedData['company_id'],
            ]);
    
            DB::commit();

            return response()->json([
                'employee' => $this->composeEmployeeData($newUser, $newEmployee),
            ], 201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'No se pudo crear el empleado.', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {

        $user = User::find($employee->auth_id)->makeHidden(['password']);

        return response()->json([
            'employee' => $employee,
            'user' => $user,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validatedData = $request->validate([
            'dni' => 'unique:users,dni,' . $employee->auth_id . '|max:9|min:9|regex:/[0-9]{8}[A-Za-z]{1}/',
            'first_name' => 'max:25|min:2',
            'last_name' => 'max:25|min:2',
            'email' => 'email|unique:users,email,' . $employee->auth_id,
            'company_id' => 'exists:companies,id', // Solo si la compañía es requerida
        ]);
    
        if (!$validatedData) {
            return response()->json("Datos no válidos.", 400);
        }
    
        // Obtener el usuario relacionado
        $user = User::find($employee->auth_id);
    
        // Actualizar Employee si es necesario
        if (isset($validatedData['company_id'])) {
            $employee->company_id = $validatedData['company_id'];
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
        $employee->save(); // Guardar cambios en la tabla employees
    
        return response()->json(['employee' => $employee], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json("Empleado despedido correctamente", 204);
    }

    public function getAllMyInsurances(Employee $employee)
    {
        $insurances = Insurance::where('employee_id', $employee->id)->get();

        return response()->json($insurances, 200);
    }

    public function getAllMyIssues(Employee $employee)
    {
        $insurances = Insurance::where('employee_id', $employee->id)->get();

        $issues = collect();

        foreach ($insurances as $insurance) {
            $iss = Issue::where('insurance_id', $insurance->id)->get();
            $issues = $issues->merge($iss);
        }

        return response()->json($issues, 200);
    }




    public function getAllMyEmployees()
    {
        $user = Auth::user();

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
