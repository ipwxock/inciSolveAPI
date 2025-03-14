<?php

namespace App\Http\Policies;

use App\Models\User;

/**
 * Define las políticas de autorización para la gestión de clientes en el sistema.
 *
 * Esta clase contiene métodos que determinan qué acciones pueden realizar los usuarios
 * según su rol dentro del sistema. Se establecen restricciones para la creación, visualización,
 * actualización y eliminación de clientes, asegurando que solo los usuarios con los permisos
 * adecuados puedan ejecutar dichas acciones.
 */
class CustomerPolicy
{
    /**
     * Determina si el usuario autenticado puede crear nuevos clientes.
     *
     * Solo los usuarios con rol 'Empleado', 'Manager' o 'Admin' tienen permiso para crear clientes.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function create(User $user)
    {
        return in_array($user->role, ['Empleado', 'Manager', 'Admin']);
    }

    /**
     * Determina si el usuario autenticado puede ver la información de un cliente.
     *
     * Los roles 'Empleado', 'Manager', 'Admin' y 'Cliente' tienen acceso a la información de clientes.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function view(User $user)
    {
        return in_array($user->role, ['Empleado', 'Manager', 'Admin', 'Cliente']);
    }

    /**
     * Determina si el usuario autenticado puede actualizar la información de un cliente.
     *
     * Solo los roles 'Manager', 'Admin' y 'Empleado' pueden modificar datos de clientes.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function update(User $user)
    {
        return in_array($user->role, ['Manager', 'Admin', 'Empleado']);
    }

    /**
     * Determina si el usuario autenticado puede eliminar clientes del sistema.
     *
     * Solo los administradores ('Admin') tienen permiso para eliminar clientes.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function delete(User $user)
    {
        return in_array($user->role, ['Admin']);
    }

    /**
     * Determina si el usuario autenticado puede ver la lista de clientes asignados a él o su equipo.
     *
     * Solo los usuarios con rol 'Empleado' o 'Manager' pueden ver sus clientes asignados.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function viewMyCustomers(User $user)
    {
        return in_array($user->role, ['Empleado', 'Manager']);
    }

     /**
     * Determina si el usuario autenticado puede ver la lista completa de clientes.
     *
     * Solo los roles 'Admin', 'Manager' y 'Empleado' tienen acceso a todos los clientes.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function viewAllCustomers(User $user)
    {
        return in_array($user->role, ['Admin', 'Manager', 'Empleado']);
    }

    /**
     * Determina si un cliente autenticado puede actualizar su propia información.
     *
     * Solo los usuarios con rol 'Cliente' pueden modificar su propio perfil.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function updateSelf(User $user)
    {
        return in_array($user->role, ['Cliente']);
    }
}
