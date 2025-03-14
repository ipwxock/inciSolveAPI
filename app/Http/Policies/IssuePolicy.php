<?php

namespace App\Http\Policies;

use App\Models\User;

/**
 * Define las políticas de autorización para la gestión de incidencias en el sistema.
 *
 * Esta clase establece qué acciones pueden realizar los usuarios sobre las incidencias,
 * dependiendo de su rol. Se definen permisos para la creación, visualización,
 * actualización y eliminación de incidencias, garantizando un control de acceso adecuado.
 */
class IssuePolicy
{
    /**
     * Determina si el usuario puede crear una nueva incidencia.
     *
     * Solo los administradores y clientes tienen permiso para registrar incidencias.
     */
    public static function create(User $user)
    {
        return in_array($user->role, ['Admin', 'Cliente']);
    }

    /**
     * Determina si el usuario puede ver la lista completa de incidencias.
     *
     * Solo los administradores tienen acceso a la lista de todas las incidencias.
     */
    public static function viewAll(User $user)
    {
        return $user->role==="Admin";
    }

    /**
     * Determina si el usuario puede actualizar la información de una incidencia.
     *
     * Los administradores, managers, empleados y clientes pueden modificar incidencias.
     */
    public static function update(User $user)
    {
        return in_array($user->role, ['Manager', 'Admin', 'Empleado', 'Cliente']);
    }

    /**
     * Determina si el usuario puede eliminar una incidencia.
     *
     * Solo los administradores tienen permiso para eliminar incidencias.
     */
    public static function delete(User $user)
    {
        return in_array($user->role, ['Admin']);
    }

    /**
     * Determina si el usuario puede ver los detalles de una incidencia.
     *
     * Los administradores, managers, empleados y clientes pueden acceder a la información detallada de una incidencia.
     */
    public static function viewDetail(User $user)
    {
        return in_array($user->role, ['Manager', 'Admin', 'Empleado', 'Cliente']);
    }

    /**
     * Determina si el usuario puede ver sus propias incidencias.
     *
     * Los empleados, managers y clientes pueden consultar las incidencias que les conciernen.
     */
    public static function viewMyIssues(User $user)
    {
        return in_array($user->role, ['Empleado', 'Cliente', 'Manager']);
    }

}
