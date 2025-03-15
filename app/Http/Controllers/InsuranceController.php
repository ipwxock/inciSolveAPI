<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Insurance;
use App\Http\Policies\InsurancePolicy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para la gestión de pólizas de seguros.
 *
 * Este controlador maneja todas las operaciones relacionadas con las pólizas de seguros, incluyendo:
 * la creación, visualización, actualización, eliminación, y consulta de pólizas según el rol del usuario.
 * Además, permite obtener pólizas específicas para el usuario o según sus permisos.
 *
 * Métodos:
 * - index(): Muestra una lista de todas las pólizas disponibles (según permisos).
 * - store(Request $request): Crea una nueva póliza en el sistema.
 * - show($id): Muestra los detalles de una póliza específica.
 * - update(Request $request, $id): Actualiza la información de una póliza existente.
 * - destroy($id): Elimina una póliza existente.
 * - getAllMyInsurances(): Obtiene todas las pólizas asociadas al usuario logueado.
 * - getInsuranceDependingOnRole(User $user): Obtiene pólizas específicas basadas en el rol del usuario.
 * - getInsuranceDetail($id): Obtiene detalles de una póliza específica, con permisos según el rol del usuario.
 */
class InsuranceController
{

    /**
     * Muestra todas las pólizas de seguro disponibles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!InsurancePolicy::viewAll($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $insurances = Insurance::all();

        // Mapear las pólizas para incluir información adicional
        $insurances = $insurances->map(function ($insurance) {
            // Obtener los datos del cliente y empleado asociados a la póliza
            $customer = Customer::find($insurance->customer_id);
            $employee = Employee::find($insurance->employee_id);

            $customerUser = User::find($customer->auth_id);
            $employeeUser = User::find($employee->auth_id);

            // Devolver la póliza con los datos enriquecidos
            return [
                'insurance' => $insurance,
                'customer' => [
                    'customer' => $customer,
                    'user' => $customerUser,
                ],
                'employee' => [
                    'employee' => $employee,
                    'user' => $employeeUser,
                ]
            ];
        });
        return response()->json($insurances, 200);
    }


    /**
     * Almacena una nueva póliza de seguro.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!InsurancePolicy::create($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $rules = [
            'subject_type' => 'required|in:Vida,Robo,Defunción,Accidente,Incendios,Asistencia_carretera,Moto,Coche,Salud,Hogar,Viaje,Mascotas,Otros',
            'description' => 'required|max:255|min:5',
            'customer_id' => 'required|exists:customers,id',
        ];

        // Si el usuario es empleado o manager, obtener `employee_id` automáticamente
        if (in_array($user->role, ['Empleado', 'Manager'])) {
            $employee = Employee::where('auth_id', $user->id)->first();

            if (!$employee) {
                return response()->json(['message' => 'No se encontró un registro de empleado para este usuario.'], 400);
            }

            $validatedData = $request->validate($rules);
            $validatedData['employee_id'] = $employee->id;
        } else {
            // Para otros roles, `employee_id` debe venir en la request
            $rules['employee_id'] = 'required|exists:employees,id';
            $validatedData = $request->validate($rules);
        }

        // Crear y retornar la póliza
        return response()->json(Insurance::create($validatedData), 201);
    }

    /**
     * Muestra los detalles de una póliza de seguro específica.
     *
     * @param \App\Models\Insurance $insurance
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Insurance $insurance)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!InsurancePolicy::view($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        return response()->json($insurance, 200);
    }

    /**
     * Actualiza los detalles de una póliza de seguro existente.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Insurance $insurance
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Insurance $insurance)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!InsurancePolicy::update($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        if ($user->role == 'Empleado' || $user->role == 'Manager') {
            $employee = Employee::where('auth_id', $user->id)->first();

            if ($employee->id !== $insurance->employee_id) {
                return response()->json(['message' => 'No autorizado.'], 403);
            }

            $request->merge(['employee_id' => $employee->id]);
        }

        $validatedData = $request->validate([
            'description' => 'max:255|min:5',
        ]);

        $employee = Employee::find($insurance->employee_id);

        if(!$validatedData) {
            return response()->json(['message' => 'Validación fallida'], 400);
        }

        $insurance->update($validatedData);

        return response()->json($insurance, 200);
    }

    /**
     * Elimina una póliza de seguro si no tiene incidencias asociadas.
     *
     * @param \App\Models\Insurance $insurance
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Insurance $insurance)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!InsurancePolicy::view($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        //Comprobar si tiene issues pendientes asociadas.
        if ($insurance->issues->contains(fn($issue) => $issue->status !== 'Cerrada')) {
            return response()->json(['message' => 'No se puede eliminar una póliza con incidencias abiertas o pendientes.'], 400);
        }


        if ($user->role === "Empleado" || $user->role === "Manager") {
            // Permitir eliminar una póliza únicamente si la ha creado el empleado
            $employee = Employee::where('auth_id', $user->id)->first();

            if (!$employee) {
                return response()->json(['message' => 'No se encontró un registro de empleado para este usuario.'], 400);
            }

            if ($insurance->employee_id !== $employee->id) {
                return response()->json(['message' => 'No tienes permisos para eliminar esta póliza.'], 403);
            }

        }

        $insurance->delete();

        return response()->json(['message'=>'Póliza eliminada correctamente.'], 200);
    }

    /**
     * Obtiene todas las pólizas de seguro asociadas al usuario autenticado.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllMyInsurances()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!InsurancePolicy::viewMyInsurances($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $insurances = $this->getInsuranceDependingOnRole($user)->get();

        // Mapear las pólizas para incluir información adicional
        $insurances = $insurances->map(function ($insurance) {
            // Obtener los datos del cliente y empleado asociados a la póliza
            $customer = Customer::find($insurance->customer_id);
            $employee = Employee::find($insurance->employee_id);

            $customerUser = User::find($customer->auth_id);
            $employeeUser = User::find($employee->auth_id);

            // Devolver la póliza con los datos enriquecidos
            return [
                'insurance' => $insurance,
                'customer' => [
                    'customer' => $customer,
                    'user' => $customerUser,
                ],
                'employee' => [
                    'employee' => $employee,
                    'user' => $employeeUser,
                ]
            ];
        });

        // Retornar el resultado en formato JSON
        return response()->json($insurances, 200);
    }

    /**
     * Obtiene las pólizas de seguro asociadas al usuario, dependiendo de su rol (Cliente o Empleado).
     *
     * @param \App\Models\User $user El usuario autenticado.
     * @return \Illuminate\Database\Eloquent\Builder La consulta para obtener las pólizas asociadas al usuario.
     */
    private function getInsuranceDependingOnRole(User $user)
    {
        if ($user->role === "Cliente") {
            $customer = Customer::where('auth_id', $user->id)->first();
            return Insurance::where('customer_id', $customer->id);
        }
        $employee = Employee::where('auth_id', $user->id)->first();
        return Insurance::where('employee_id', $employee->id);
    }

    /**
     * Obtiene los detalles de una póliza de seguro específica.
     *
     * @param \App\Models\Insurance $insurance
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInsuranceDetail(Insurance $insurance)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!InsurancePolicy::view($user) || !$this->isMyInsurance($user, $insurance)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $customer = Customer::find($insurance->customer_id);
        $employee = Employee::find($insurance->employee_id);

        $customerUser = User::find($customer->auth_id);
        $employeeUser = User::find($employee->auth_id);

        return response()->json([
            'insurance' => $insurance,
            'customer' => [
                'customer' => $customer,
                'user' => $customerUser,
            ],
            'employee' => [
                'employee' => $employee,
                'user' => $employeeUser,
            ],
            'issues' => $insurance->issues
        ], 200);
    }

    /**
     * Verifica si una póliza es del usuario autenticado, basándose en su rol y eld id de la póliza.
     *
     * @param \App\Models\User $user El usuario autenticado.
     * @param \App\Models\Insurance $insurance La póliza a verificar.
     * @return bool True si la póliza es del usuario, false de lo contrario.
     */
    private function isMyInsurance(User $user, Insurance $insurance)
    {
        if ($user->role==="Cliente") {
            return $user->customer->id === $insurance->customer_id;
        }

        if ($user->role==="Empleado" || $user->role==="Manager") {
            return $user->employee->id === $insurance->employee_id;
        }
    }
}
