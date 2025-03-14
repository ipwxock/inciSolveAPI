<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable; // Cambia Model a Authenticatable
use Laravel\Sanctum\HasApiTokens;

/**
 * Clase que representa a un usuario en el sistema.
 *
 * Esta clase maneja la información relacionada con los usuarios autenticados, incluyendo sus datos personales,
 * credenciales de acceso y el rol asignado dentro del sistema (Admin, Manager, Cliente, etc.).
 *
 * Propiedades:
 * - dni (string): Documento Nacional de Identidad del usuario.
 * - first_name (string): Primer nombre del usuario.
 * - last_name (string): Apellido del usuario.
 * - email (string): Dirección de correo electrónico del usuario.
 * - password (string): Contraseña encriptada del usuario.
 * - role (string): Rol asignado al usuario dentro del sistema (Admin, Manager, Cliente, Empleado).
 *
 * Relaciones:
 * - customer(): Establece una relación de "uno a uno" con el modelo `Customer`, indicando que un usuario
 *   puede estar asociado a un cliente específico.
 * - employee(): Establece una relación de "uno a uno" con el modelo `Employee`, indicando que un usuario puede
 *   estar asociado a un empleado específico.
 *
 * La clase utiliza los traits `HasApiTokens` y `HasFactory` para facilitar la creación de tokens API y la creación
 * de modelos mediante fábricas. Los registros de esta clase gestionan los timestamps de creación/actualización
 * debido a que `timestamps` está configurado como `true`.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'dni',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'auth_id');
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'auth_id');
    }

    public $timestamps = true;
}
