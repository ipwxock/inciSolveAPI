<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Policies\UserPolicy;
use App\Models\Employee;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Log;

/**
 * Controlador para la gestión de usuarios.
 *
 * Este controlador maneja todas las operaciones relacionadas con los usuarios, incluyendo:
 * la creación, visualización, actualización y eliminación de usuarios. Además, permite
 * obtener una lista de todos los usuarios con sus relaciones (admin, manager, employee, customer).
 *
 * Métodos:
 * - index(): Muestra una lista de todos los usuarios con sus relaciones.
 * - store(Request $request): Crea un nuevo usuario y la entidad asociada según el rol (Empleado, Manager, Cliente).
 * - show($id): Muestra los detalles de un usuario específico.
 * - update(Request $request, $id): Actualiza la información de un usuario existente y su entidad asociada.
 * - destroy($id): Elimina un usuario del sistema.
 */
class UserController
{

    /**
     * Obtiene una lista de todos los usuarios con sus relaciones (admin, manager, employee, customer).
     * Se oculta la contraseña de cada usuario antes de devolver la respuesta.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta con todos los usuarios, excluyendo la contraseña.
     */
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!UserPolicy::viewAll($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $users = User::all();

        // Ocultar la contraseña de cada usuario
        $users->makeHidden(['password']);

        return response()->json($users, 200);
    }

    /**
     * Crea un nuevo usuario y la entidad asociada según el rol (Empleado, Manager, Cliente).
     * Valida los datos de entrada y asegura que el usuario tenga los permisos necesarios.
     *
     * @param \Illuminate\Http\Request $request Los datos del nuevo usuario.
     * @return \Illuminate\Http\JsonResponse Respuesta con el estado de la creación.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $user = Auth::user();

        if (!UserPolicy::create($user)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }
        // Validar todos los datos de entrada
        $validatedData = $this->validateUserData($request);

        if (!empty($validatedData['company_id']) && $validatedData['role'] === 'Manager'){
            $manager = Employee::where('company_id', $validatedData['company_id'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'Manager');
            })
            ->first();

            if ($manager) {
                return response()->json(['message' => 'Ya existe un manager para esta compañía.'], 400);
            }
        }

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
        return response()->json(['message' => "Creado con éxito"], 201);
    }


    /**
     * Muestra los detalles de un usuario específico. Si el usuario tiene un rol de Empleado o Manager,
     * se añade la entidad Employee correspondiente, si es Cliente, se añade la entidad Customer.
     *
     * @param \App\Models\User $userToShow El usuario cuyo detalle se va a mostrar.
     * @return \Illuminate\Http\JsonResponse Respuesta con los detalles del usuario o mensaje de error.
     */
    public function show(User $user)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $userRequesting = Auth::user();

        if (!UserPolicy::view($userRequesting)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->role === 'Empleado' || $user->role === 'Manager') {
            $employee = Employee::where('auth_id', $user->id)->first();
            if(!$employee){
                return response()->json($user->makeHidden("password"), 200);
            }
            $user->company_id = $employee->company_id;
        }

        if ($user->role === 'Cliente') {
            $customer = Customer::where('auth_id', $user->id)->first();
            if(!$customer){
                return response()->json($user->makeHidden("password"), 200);
            }
            $user->phone_number = $customer->phone_number;
            $user->address = $customer->address;
        }

        return response()->json($user->makeHidden("password")->toArray(), 200);
    }


    /**
     * Actualiza la información de un usuario existente y su entidad asociada según el rol (Empleado, Manager, Cliente).
     * Valida los datos de entrada antes de realizar la actualización.
     *
     * @param \Illuminate\Http\Request $request Los nuevos datos para actualizar al usuario.
     * @param \App\Models\User $user El usuario que se va a actualizar.
     * @return \Illuminate\Http\JsonResponse Respuesta con el estado de la actualización.
     */
    public function update(Request $request, User $user)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $editor = Auth::user();

        if (!UserPolicy::update($editor)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        if($user->role !== $request['role']){
            return response()->json(['message' => 'No se puede cambiar el rol de un usuario'], 400);
        }

        $validatedData = $this->validateUserData($request, $user);

        if (!$validatedData) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $user->update($validatedData);

        $this->updateRelatedEntity($user, $validatedData);

        return response()->json(["message" => 'Modificado con éxito.'], 200);
    }

    /**
     * Elimina un usuario del sistema, siempre que el usuario tenga los permisos necesarios.
     *
     * @param \App\Models\User $user El usuario que se va a eliminar.
     * @return \Illuminate\Http\JsonResponse Respuesta con el estado de la eliminación.
     */
    public function destroy(User $user)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $deleter = Auth::user();

        if (!UserPolicy::delete($deleter)) {
            return response()->json(['message' => 'No autorizad@'], 403);
        }

        $userToDelete = User::findOrfail($user->id);

        if (!$userToDelete || $userToDelete->role === 'Admin') {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if ($userToDelete->role === 'Manager' || $userToDelete->role === 'Empleado') {
            $employee = Employee::where('auth_id', $userToDelete->id)->first();
            if ($employee) {
                $insurances = $employee->insurances;
                if ($insurances->count() > 0) {
                    return response()->json(['message' => 'No se puede eliminar el usuario porque tiene pólizas asociadas'], 400);
                }
            }
        }

        if ($userToDelete->role === 'Cliente') {
            $customer = Customer::where('auth_id', $userToDelete->id)->first();
            if ($customer) {
                $insurances = $customer->insurances;
                if ($insurances->count() > 0) {
                    return response()->json(['message' => 'No se puede eliminar el usuario porque tiene pólizas asociadas'], 400);
                }
            }
        }

        $user->delete();

        return response()->json(['message' => 'Usuario borrado'], 200);
    }


    /**
     * Crea la entidad relacionada con el usuario (Empleado, Manager, Cliente) según su rol.
     * Si el rol es Empleado o Manager, se requiere un 'company_id'.
     * Si el rol es Cliente, se requieren los campos 'phone_number' y 'address'.
     *
     * @param \App\Models\User $user El usuario que está siendo creado.
     * @param array $data Los datos del usuario para crear la entidad relacionada.
     * @return \Illuminate\Http\JsonResponse Respuesta con el estado de la creación de la entidad.
     */
    private function createRelatedEntity(User $user, array $data)
    {
        try {
                switch ($data['role']) {
                case 'Empleado':
                    $companyId = Auth::user()->role === 'Admin' ? $data['company_id'] : Auth::user()->employee->company_id;


                    if (!$companyId) {
                        Log::error('No se ha especificado una compañía.');
                        return response()->json(['error' => 'No se ha especificado una compañía.'], 400);
                    }

                    $employee = Employee::create([
                        'auth_id' => $user->id,
                        'company_id' => $companyId,
                    ]);


                    $employee->save();
                    break;

                case 'Manager':
                    $companyId = $data['company_id'];


                    if (!$companyId) {
                        Log::error('No se ha especificado una compañía.');
                        return response()->json(['error' => 'No se ha especificado una compañía.'], 400);
                    }

                    $employee = Employee::create([
                        'auth_id' => $user->id,
                        'company_id' => $companyId,
                    ]);


                    $employee->save();
                    break;

                case 'Cliente':
                    $customer = Customer::create([
                        'auth_id' => $user->id,
                        'phone_number' => $data['phone_number'],
                        'address' => $data['address'],
                    ]);


                    $customer->save();
                    break;
            }


            $user->save();


        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Actualiza la entidad relacionada con el usuario (Empleado, Manager, Cliente) según su rol.
     * Si el rol es Empleado o Manager, se actualiza el 'company_id'.
     * Si el rol es Cliente, se actualizan los campos 'phone_number' y 'address'.
     *
     * @param \App\Models\User $user El usuario que está siendo actualizado.
     * @param array $data Los datos actualizados para la entidad relacionada.
     * @return \Illuminate\Http\JsonResponse Respuesta con el estado de la actualización de la entidad.
     */
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
                    $employee->save();
                    break;

                case 'Cliente':
                    $customer = Customer::where('auth_id', $user->id)->first();
                    $customer->update([
                        'phone_number' => $data['phone_number'],
                        'address' => $data['address'],
                    ]);
                    $customer->save();
                    break;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Valida los datos de entrada para la creación o actualización de un usuario, incluyendo la validación de:
     * 'dni', 'first_name', 'last_name', 'email', 'role', 'phone_number', 'address', y 'company_id' según sea necesario.
     * Si el usuario ya existe, se ignoran los datos existentes para 'dni' y 'email'.
     *
     * @param \Illuminate\Http\Request $request Los datos del usuario a validar.
     * @param \App\Models\User|null $user El usuario a actualizar (opcional).
     * @return array Los datos validados para la creación o actualización del usuario.
     */
    public function validateUserData(Request $request, User $user = null)
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
