<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Clase que representa a un empleado.
 *
 * Esta clase gestiona la información relacionada con los empleados en el sistema, incluyendo el usuario asociado
 * y la empresa a la que pertenece. Además, proporciona métodos para acceder a las pólizas de seguros asociadas al empleado.
 *
 * Propiedades:
 * - auth_id (integer): El identificador del usuario autenticado asociado al empleado.
 * - company_id (integer): El identificador de la empresa a la que pertenece el empleado.
 *
 * Relaciones:
 * - companie(): Establece una relación de "pertenencia" con el modelo `Company`, lo que indica que el empleado está
 *   asociado a una empresa específica.
 * - user(): Establece una relación de "pertenencia" con el modelo `User`, indicando que el empleado está asociado a un usuario
 *   a través de su `auth_id`.
 * - insurances(): Establece una relación uno a muchos con el modelo `Insurance`, lo que significa que un empleado puede estar
 *   asociado a varias pólizas de seguros.
 *
 * Esta clase utiliza el trait `HasFactory` para permitir la creación de fábricas de modelos y la interacción con la base de datos
 * mediante Eloquent. Los registros de esta clase no gestionan los timestamps de creación/actualización debido a que
 * `timestamps` está configurado como `false`.
 */
class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = [
        'auth_id',
        'company_id'
    ];

    public $timestamps = false;


    public function companie() : BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_id');
    }

    public function insurances() : HasMany
    {
        return $this->hasMany(Insurance::class);
    }

}
