<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Issue;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Clase que representa una póliza de seguro.
 *
 * Esta clase gestiona la información relacionada con las pólizas de seguro en el sistema, incluyendo el tipo de
 * sujeto, la descripción, el cliente y el empleado asociado, así como las incidencias relacionadas con la póliza.
 *
 * Propiedades:
 * - subject_type (string): El tipo de sujeto de la póliza (ej. tipo de seguro, etc.).
 * - description (string): La descripción de la póliza de seguro.
 * - customer_id (integer): El identificador del cliente asociado a la póliza.
 * - employee_id (integer): El identificador del empleado que gestiona la póliza.
 *
 * Relaciones:
 * - customer(): Establece una relación de "pertenencia" con el modelo `Customer`, indicando que la póliza está asociada
 *   a un cliente específico.
 * - issues(): Establece una relación uno a muchos con el modelo `Issue`, lo que significa que una póliza puede estar
 *   asociada a varias incidencias.
 * - employee(): Establece una relación de "pertenencia" con el modelo `Employee`, indicando que un empleado está asociado
 *   a la póliza.
 *
 * Esta clase utiliza el trait `HasFactory` para facilitar la creación de fábricas de modelos y la interacción con la base de
 * datos mediante Eloquent. Los registros de esta clase gestionan los timestamps de creación/actualización debido a que
 * `timestamps` está configurado como `true`.
 */
class Insurance extends Model
{
    use HasFactory;

    protected $table = 'insurances';
    protected $fillable = [
        'subject_type',
        'description',
        'customer_id',
        'employee_id',
    ];

    function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    function issues() : HasMany
    {
        return $this->hasMany(Issue::class);
    }

    function employee() : BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public $timestamps = true;
}
