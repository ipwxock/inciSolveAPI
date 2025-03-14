<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Insurance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Clase que representa a un cliente.
 *
 * Esta clase se encarga de gestionar la información relacionada con los clientes en el sistema.
 * Proporciona propiedades y métodos para manejar los datos de un cliente, como su identificación de autenticación,
 * número de teléfono y dirección. También define las relaciones con las pólizas de seguros y el usuario asociado al cliente.
 *
 * Propiedades:
 * - auth_id (integer): El identificador del usuario autenticado asociado al cliente.
 * - phone_number (string): El número de teléfono del cliente.
 * - address (string): La dirección del cliente.
 *
 * Relaciones:
 * - insurances(): Establece una relación uno a muchos con el modelo `Insurance`,
 *   lo que significa que un cliente puede tener varias pólizas de seguros asociadas.
 * - user(): Establece una relación de "pertenencia" con el modelo `User`,
 *   lo que indica que cada cliente está asociado a un usuario específico del sistema a través de su `auth_id`.
 *
 * Esta clase usa el trait `HasFactory` para la creación de fábricas de modelos y permite la interacción con
 * la base de datos a través de Eloquent. Además, los registros de esta clase no gestionan los timestamps de creación/actualización
 * debido a que `timestamps` está configurado como `false`.
 */
class Customer extends Model
{
//
    use hasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'auth_id',
        'phone_number',
        'address',
    ];
    public $timestamps = false;
    function insurances() : HasMany
    {
        return $this->hasMany(Insurance::class);
    }

    function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_id');
    }

}
