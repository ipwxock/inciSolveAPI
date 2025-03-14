<?php

namespace App\Http\Policies;

use App\Models\User;

/**
 * Define las políticas de autorización para la gestión de pólizas de seguros en el sistema.
 *
 * Esta clase contiene métodos que determinan qué acciones pueden realizar los usuarios
 * sobre las pólizas de seguros, según su rol. Se establecen restricciones para la
 * creación, visualización, actualización y eliminación de pólizas, asegurando que
 * solo los usuarios con permisos adecuados puedan ejecutar dichas acciones.
 */
class InsurancePolicy
{
    /**
     * Determina si el usuario puede crear una nueva póliza de seguro.
     *
     * Solo los empleados, managers y administradores tienen permiso para crear pólizas.
     */
    public static function create(User $user)
    {
        return in_array($user->role, ['Empleado', 'Manager', 'Admin']);
    }

    /**
     * Determina si el usuario puede ver la información de una póliza de seguro.
     *
     * Los empleados, managers, administradores y clientes pueden visualizar pólizas.
     */
    public static function view(User $user)
    {
        return in_array($user->role, ['Empleado', 'Manager', 'Admin', 'Cliente']);
    }

    /**
     * Determina si el usuario puede actualizar la información de una póliza de seguro.
     *
     * Solo los empleados, managers y administradores tienen permiso para actualizar pólizas.
     */
    public static function update(User $user)
    {
        return in_array($user->role, ['Manager', 'Admin', 'Empleado']);
    }

    /**
     * Determina si el usuario puede eliminar una póliza de seguro.
     *
     * Solo los empleados, managers y administradores tienen permiso para eliminar pólizas.
     */
    public static function delete(User $user)
    {
        return in_array($user->role, ['Manager', 'Admin', 'Empleado']);
    }

    /**
     * Determina si el usuario puede ver la lista completa de pólizas de seguro.
     *
     * Solo los administradores tienen acceso a la lista de todas las pólizas.
     */
    public static function viewAll(User $user)
    {
        return in_array($user->role, ['Admin']);
    }

    /**
     * Determina si el usuario puede ver sus propias pólizas de seguro.
     *
     * Los empleados, managers y clientes pueden visualizar las pólizas asociadas a ellos.
     */
    public static function viewMyInsurances(User $user)
    {
        return in_array($user->role, ['Empleado', 'Manager', 'Cliente']);
    }
}
