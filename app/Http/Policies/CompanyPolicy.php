<?php

namespace App\Http\Policies;

use App\Models\User;
use App\Models\Company;

/**
 * Define las políticas de acceso y permisos para la gestión de compañías.
 *
 * Esta clase contiene métodos que determinan qué acciones puede realizar un usuario
 * en relación con las compañías, dependiendo de su rol en el sistema.
 *
 * Los roles considerados en esta política son:
 * - Admin: Tiene acceso total a todas las operaciones.
 * - Manager: Puede ver y gestionar compañías si está asociado a ellas.
 * - Empleado y Cliente: Pueden visualizar ciertas compañías según sus permisos.
 *
 * Se incluyen métodos para verificar permisos en operaciones como creación,
 * visualización, actualización y eliminación de compañías.
 */
class CompanyPolicy{

        /**
         * Determina si el usuario puede crear una nueva compañía.
         *
         * @param User $user Usuario autenticado.
         * @return bool Verdadero si el usuario es un Admin.
         */
        public static function create(User $user) {
            return in_array($user->role, ['Admin']);
        }

        /**
         * Determina si el usuario puede ver todas las compañías.
         *
         * @param User $user Usuario autenticado.
         * @return bool Verdadero si el usuario tiene un rol autorizado.
         */
        public static function viewAll(User $user) {
            return in_array($user->role, ['Empleado', 'Manager', 'Admin', 'Cliente']);
        }

        /**
         * Determina si el usuario puede ver los detalles de una compañía específica.
         *
         * @param User $user Usuario autenticado.
         * @param Company $company Compañía a visualizar.
         * @return bool Verdadero si el usuario es Admin o es el Manager de la compañía.
         */
        public static function viewDetail(User $user, Company $company) {
            return $user->role === 'Admin' || ($user->role === 'Manager' && self::isTheManager($user, $company));
        }

        /**
         * Determina si el usuario puede actualizar los datos de una compañía.
         *
         * @param User $user Usuario autenticado.
         * @param Company $company Compañía a actualizar.
         * @return bool Verdadero si el usuario es Admin o es el Manager de la compañía.
         */
        public static function update(User $user, Company $company) {
            return $user->role === 'Admin' || ($user->role === 'Manager' && self::isTheManager($user, $company));
        }

        /**
         * Determina si el usuario puede eliminar una compañía.
         *
         * @param User $user Usuario autenticado.
         * @return bool Verdadero si el usuario es Admin.
         */
        public static function delete(User $user) {
            return in_array($user->role, ['Admin']);
        }

        /**
         * Determina si el usuario puede ver una compañía.
         *
         * @param User $user Usuario autenticado.
         * @return bool Verdadero si el usuario es Admin.
         */
        public static function view(User $user) {
            return in_array($user->role, ['Admin']);
        }

        /**
         * Verifica si el usuario es el Manager de la compañía.
         *
         * @param User $user Usuario autenticado.
         * @param Company $company Compañía a verificar.
         * @return bool Verdadero si el usuario es Manager y está asociado a la compañía.
         */
        private static function isTheManager(User $user, Company $company) {
            return $user->employee && $user->employee->company_id === $company->id;
        }

}
