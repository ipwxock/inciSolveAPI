<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Insurance;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Policies\IssuePolicy;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador para la gestión de incidencias.
 *
 * Este controlador maneja todas las operaciones relacionadas con las incidencias, incluyendo:
 * la creación, visualización, actualización, eliminación y consulta de incidencias. Además, permite
 * verificar si un usuario tiene acceso a una incidencia específica según su rol.
 *
 * Métodos:
 * - index(): Muestra una lista de todas las incidencias disponibles según el rol del usuario.
 * - store(Request $request): Crea una nueva incidencia en el sistema.
 * - show($id): Muestra los detalles de una incidencia específica.
 * - update(Request $request, $id): Actualiza la información de una incidencia existente.
 * - destroy($id): Elimina una incidencia existente.
 * - getAllMyIssues(): Obtiene todas las incidencias asociadas al usuario logueado.
 * - isMyIssue(User $user, Issue $issue): Verifica si el usuario tiene acceso a la incidencia basada en su rol.
 */

class IssueController
{

    /**
     * Muestra una lista de todas las incidencias si el usuario tiene los permisos necesarios.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta con todas las incidencias o mensaje de error.
     */
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!IssuePolicy::viewAll($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $issues = Issue::all();

        $issues = $issues->map(function ($issue) {
            $insurance = Insurance::find($issue->insurance_id);
            $customer = Customer::find($insurance->customer_id);
            $employee = Employee::find($insurance->employee_id);

            $customerUser = $customer ? User::find($customer->auth_id) : null;
            $employeeUser = $employee ? User::find($employee->auth_id) : null;

            return [
                'issue' => $issue,
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

        return response()->json($issues, 200);
    }

    /**
     * Crea una nueva incidencia basada en los datos proporcionados en la solicitud.
     *
     * @param \Illuminate\Http\Request $request Datos de la incidencia a crear.
     * @return \Illuminate\Http\JsonResponse Respuesta con la incidencia creada o mensaje de error.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!IssuePolicy::create($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $validatedData = $request->validate([
            'insurance_id' => 'required|exists:insurances,id',
            'subject' => 'required|string',
            'status' => 'required|in:Abierta,Cerrada,Pendiente',
        ]);

        $issue = Issue::create($validatedData);

        return response()->json($issue, 201);
    }

    /**
     * Actualiza una incidencia existente con los nuevos datos proporcionados en la solicitud.
     *
     * @param \Illuminate\Http\Request $request Datos de la incidencia a actualizar.
     * @param \App\Models\Issue $issue La incidencia a actualizar.
     * @return \Illuminate\Http\JsonResponse Respuesta con la incidencia actualizada o mensaje de error.
     */
    public function show(Issue $issue)
    {

        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!IssuePolicy::viewDetail($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        if($user->role!=="Admin" && !$this->isMyIssue($user, $issue)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $insurance = Insurance::find($issue->insurance_id);
        $customer = Customer::find($insurance->customer_id);
        $employee = Employee::find($insurance->employee_id);

        $customerUser = $customer ? User::find($customer->auth_id) : null;
        $employeeUser = $employee ? User::find($employee->auth_id) : null;

        return response()->json([
            'issue' => $issue,
            'insurance' => $insurance,
            'customer' => [
                'customer' => $customer,
                'user' => $customerUser,
            ],
            'employee' => [
                'employee' => $employee,
                'user' => $employeeUser,
            ]
        ], 200);
    }

    /**
     * Elimina una incidencia existente si el usuario tiene los permisos necesarios.
     *
     * @param \App\Models\Issue $issue La incidencia a eliminar.
     * @return \Illuminate\Http\JsonResponse Respuesta con un mensaje de éxito o error.
     */
    public function update(Request $request, Issue $issue)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!IssuePolicy::update($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $validatedData = $request->validate([
            'insurance_id' => 'exists:insurances,id',
            'subject' => 'string',
            'status' => 'in:Abierta,Cerrada,Pendiente',
        ]);

        $issue->update($validatedData);

        return response()->json($issue, 200);
    }

    /**
     * Elimina una incidencia existente si el usuario tiene los permisos necesarios.
     *
     * @param \App\Models\Issue $issue La incidencia a eliminar.
     * @return \Illuminate\Http\JsonResponse Respuesta con un mensaje de éxito o error.
     */
    public function destroy(Issue $issue)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!IssuePolicy::delete($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $issue->delete();

        return response()->json(null, 200);
    }

    /**
     * Obtiene todas las incidencias asociadas al usuario autenticado, dependiendo de su rol (Empleado, Manager, Cliente).
     *
     * @return \Illuminate\Http\JsonResponse Respuesta con todas las incidencias asociadas o mensaje de error.
     */
    public function getAllMyIssues()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if(!IssuePolicy::viewMyIssues($user)) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        if($user->role==="Empleado" || $user->role==="Manager") {
            $employee = Employee::where('auth_id', $user->id)->first();
            $insurances = $employee->insurances;
        } else {
            $customer = Customer::where('auth_id', $user->id)->first();
            $insurances = $customer->insurances;
        }

        $issues = [];

        if (!$insurances->isEmpty()) {
            $issues = $insurances->map(function ($insurance) {
                $insurance->load('issues'); // Cargar las incidencias de la póliza
                return $insurance->issues;
            })->flatten(); // Asegurarse de que el resultado sea un array plano
        }

        $issues = $issues->map(function ($issue) {
            $insurance = Insurance::find($issue->insurance_id);
            $customer = Customer::find($insurance->customer_id);
            $employee = Employee::find($insurance->employee_id);

            $customerUser = $customer ? User::find($customer->auth_id) : null;
            $employeeUser = $employee ? User::find($employee->auth_id) : null;

            return [
                'issue' => $issue,
                'insurance' => $insurance,
                'customer' => [
                    'customer' => $customer,
                    'user' => $customerUser,
                ],
                'employee' => [
                    'employee' => $employee,
                    'user' => $employeeUser,
                ],
            ];
        });

        return response()->json($issues, 200);

    }

    /**
     * Verifica si una incidencia es del usuario autenticado, basándose en su rol y la póliza asociada a la incidencia.
     *
     * @param \App\Models\User $user El usuario autenticado.
     * @param \App\Models\Issue $issue La incidencia a verificar.
     * @return bool True si la incidencia es del usuario, false de lo contrario.
     */
    private function isMyIssue(User $user, Issue $issue)
    {
        if ($user->role==="Cliente") {
            return $user->customer->id === $issue->insurance->customer_id;
        }

        if ($user->role==="Empleado" || $user->role==="Manager") {
            return $user->employee->id === $issue->insurance->employee_id;
        }
    }
}
