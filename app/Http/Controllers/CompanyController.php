<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Policies\CompanyPolicy;


/**
 * Controlador para la gestión de aseguradoras.
 *
 * Este controlador maneja todas las operaciones relacionadas con las aseguradoras, incluyendo:
 * la creación, visualización, actualización y eliminación de aseguradoras. Además, permite
 * obtener el ID de la aseguradora administrada por el usuario si tiene el rol de "Manager".
 *
 * Métodos:
 * - index(): Muestra una lista de todas las aseguradoras disponibles.
 * - store(Request $request): Crea una nueva aseguradora en el sistema.
 * - show($id): Muestra los detalles de una aseguradora específica.
 * - update(Request $request, $id): Actualiza la información de una aseguradora existente.
 * - destroy($id): Elimina una aseguradora existente.
 * - getMyCompanyId(): Obtiene el ID de la aseguradora administrada por el usuario si tiene el rol de "Manager".
 * - viewSimple($id): Muestra los detalles de una aseguradora específica.
 */
class CompanyController
{
    /**
     * Devuelve una lista de todas las aseguradoras si el usuario está autenticado y autorizado.
     *
     * @return \Illuminate\Http\JsonResponse Lista de aseguradoras en formato JSON o un mensaje de error si no está autorizado.
     */
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!CompanyPolicy::viewAll($user)) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json(Company::all(), 200);
    }

    /**
     * Crea una nueva aseguradora si el usuario está autenticado y tiene permisos.
     *
     * @param Request $request Contiene los datos validados de la aseguradora.
     * @return \Illuminate\Http\JsonResponse Devuelve la aseguradora creada o un mensaje de error si no está autorizado.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!CompanyPolicy::create($user)) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|max:50|min:5',
            'description' => 'max:255|min:5',
            'phone_number' => 'nullable|regex:/^[0-9]{9}$/',
        ]);

        if (!$validatedData) {
            return response()->json(['message' => 'Validación fallida'], 400);
        }

        $company = Company::create($validatedData);

        return response()->json($company, 201);
    }


    /**
     * Muestra los detalles de una aseguradora específica, incluyendo empleados, pólizas e incidencias, si el usuario tiene acceso.
     *
     * @param Company $company La aseguradora a mostrar.
     * @return \Illuminate\Http\JsonResponse Detalles de la aseguradora y sus relaciones o un mensaje de error si no está autorizado.
     */
    public function show(Company $company)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!CompanyPolicy::viewDetail($user, $company)) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Buscamos los empleados de la aseguradora
        $employees = $company->employees()->with('user')->get()->map(function ($employee) {
            return [
                'employee' => $employee,
                'user' => $employee->user
            ];
        });

        // Buscamos las pólizas de los empleados
        $insurances = $company->employees()->with('insurances')->get()->flatMap->insurances;

        // Buscamos las incidencias de las pólizas
        $issues = $insurances->flatMap->issues;

        // Retornamos la información
        return response()->json([
            'company' => $company,
            'employees' => $employees,
            'insurances' => $insurances,
            'issues' => $issues
        ], 200);
    }


    /**
     * Actualiza la información de una aseguradora si el usuario tiene permisos adecuados.
     *
     * @param Request $request Contiene los datos actualizados de la aseguradora.
     * @param Company $company La aseguradora a actualizar.
     * @return \Illuminate\Http\JsonResponse Devuelve la aseguradora actualizada o un mensaje de error si no está autorizado.
     */
    public function update(Request $request, Company $company)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user(); // Llamamos a Auth::user() solo después de confirmar la autenticación

        if (!CompanyPolicy::update($user, $company)) {
            return response()->json(['message' => 'No tienes permiso para actualizar esta empresa'], 403);
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
     * Elimina una aseguradora si el usuario tiene permisos adecuados.
     *
     * @param Company $company La aseguradora a eliminar.
     * @return \Illuminate\Http\JsonResponse Mensaje de confirmación o error si el usuario no tiene permisos.
     */
    public function destroy(Company $company)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if(!CompanyPolicy::delete($user)){
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $employees = $company->employees;

        if ($employees->count() > 0) {
            return response()->json(['message' => 'No se puede eliminar la aseguradora porque tiene empleados asociados'], 400);
        }

        $company->delete();

        return response()->json(["message"=>"Aseguradora eliminada con éxito."], 200);
    }

    /**
     * Obtiene el ID de la aseguradora administrada por el usuario si tiene el rol de "Manager".
     *
     * @return \Illuminate\Http\JsonResponse Devuelve el ID de la aseguradora o un mensaje de error si no está autorizado.
     */
    public function getMyCompanyId()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if(!in_array($user->role, ['Manager'])){
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $manager = Employee::where('auth_id', $user->id)->first();

        $company = Company::where('id', $manager->company_id)->first();

        if (!$company) {
            return response()->json(['message' => 'Aseguradora no encontrada'], 404);
        }

        return response()->json($company->id, 200);
    }


    /**
     * Obtiene una lista de todas las aseguradoras si el usuario está autenticado y autorizado.
     *
     * @return \Illuminate\Http\JsonResponse Lista de aseguradoras en formato JSON o un mensaje de error si no está autorizado.
     */
    public function viewSimple(Company $company)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if(!CompanyPolicy::view($user)){
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($company, 200);
    }

}
