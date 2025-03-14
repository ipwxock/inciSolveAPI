<?php

namespace App\Http\Policies;

use App\Models\User;

/**
 * Define las reglas de acceso y permisos para la gestión de usuarios.
 *
 * Esta clase determina qué acciones puede realizar un usuario en relación con otros usuarios,
 * basándose en su rol dentro del sistema.
 *
 * Los roles considerados en esta política son:
 * - Admin: Tiene control total sobre la gestión de usuarios.
 * - Manager: Puede crear y actualizar usuarios, pero con ciertas restricciones.
 * - Empleado y Cliente: Pueden actualizar su propia información, pero con permisos limitados.
 *
 * Se incluyen métodos para validar permisos en operaciones como creación, visualización,
 * actualización y eliminación de usuarios.
 */
class UserPolicy
{
    /**
     * Determina si el usuario autenticado puede crear nuevos usuarios.
     *
     * Solo los usuarios con rol 'Empleado', 'Manager' o 'Admin' tienen permiso para crear usuarios.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function create(User $user)
    {
        return in_array($user->role, ['Empleado', 'Manager', 'Admin']);
    }

    /**
     * Determina si el usuario autenticado puede ver los detalles de un usuario específico.
     *
     * Solo los administradores ('Admin') tienen permiso para ver información detallada de los usuarios.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function view(User $user)
    {
        return in_array($user->role, ['Admin']);
    }

    /**
     * Determina si el usuario autenticado puede actualizar la información de un usuario.
     *
     * Los roles 'Manager', 'Admin', 'Empleado' y 'Cliente' pueden realizar actualizaciones.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function update(User $user)
    {
        return in_array($user->role, ['Manager', 'Admin', 'Empleado', 'Cliente']);
    }

    /**
     * Determina si el usuario autenticado puede eliminar usuarios del sistema.
     *
     * Solo los administradores ('Admin') tienen permiso para eliminar usuarios.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function delete(User $user)
    {
        return in_array($user->role, ['Admin']);
    }

    /**
     * Determina si el usuario autenticado puede ver la lista de todos los usuarios.
     *
     * Solo los administradores ('Admin') tienen acceso a la lista completa de usuarios.
     *
     * @param User $user El usuario autenticado.
     * @return bool Verdadero si el usuario tiene permisos, falso en caso contrario.
     */
    public static function viewAll(User $user)
    {
        return in_array($user->role, ['Admin']);
    }
}
