<?php

namespace App\Http\Policies;

use App\Models\User;

/**
 * Define las políticas de autorización para la gestión de empleados en el sistema.
 *
 * Esta clase contiene métodos que determinan qué acciones pueden realizar los usuarios
 * sobre los empleados, basándose en su rol dentro del sistema. Se establecen restricciones
 * para la creación, visualización, actualización y eliminación de empleados, asegurando
 * que solo los usuarios con los permisos adecuados puedan ejecutar dichas acciones.
 */
class EmployeePolicy
{
    /**
     * Determina si el usuario puede crear un nuevo empleado.
     *
     * Solo los usuarios con rol de Manager o Admin pueden realizar esta acción.
     */
    public static function create(User $user)
    {
        return in_array($user->role, ['Manager', 'Admin']);
    }

    /**
     * Determina si el usuario puede ver la información de un empleado.
     *
     * Los empleados, managers y administradores tienen permiso para visualizar empleados.
     */
    public static function view(User $user)
    {
        return in_array($user->role, ['Empleado', 'Manager', 'Admin']);
    }

    /**
     * Determina si el usuario puede ver la lista completa de empleados.
     *
     * Solo los administradores tienen acceso a la lista de todos los empleados.
     */
    public static function viewAll(User $user)
    {
        return in_array($user->role, ['Admin']);
    }

    /**
     * Determina si el usuario puede actualizar la información de un empleado.
     *
     * Solo los usuarios con rol de Manager o Admin pueden actualizar empleados.
     */
    public static function update(User $user)
    {
        return in_array($user->role, ['Manager', 'Admin']);
    }

    /**
     * Determina si el usuario puede eliminar un empleado.
     *
     * Solo los administradores tienen permiso para eliminar empleados.
     */
    public static function delete(User $user)
    {
        return in_array($user->role, ['Admin']);
    }

    /**
     * Determina si el usuario puede ver los empleados de su aseguradora.
     *
     * Solo los managers tienen acceso a la lista de sus empleados.
     */
    public static function viewMyEmployees(User $user)
    {
        return in_array($user->role, ['Manager']);
    }
}
