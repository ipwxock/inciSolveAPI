<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Clase que representa una empresa.
 *
 * Esta clase se encarga de gestionar la información relacionada con las empresas en el sistema.
 * Proporciona propiedades y métodos para manejar los datos de una empresa, como su nombre, descripción
 * y número de teléfono. También define la relación con los empleados asociados a la empresa.
 *
 * Propiedades:
 * - name (string): El nombre de la empresa.
 * - description (string): Una descripción de la empresa.
 * - phone_number (string): El número de teléfono de la empresa.
 *
 * Relaciones:
 * - employees(): Establece una relación uno a muchos con el modelo Employee,
 *   lo que significa que una empresa puede tener muchos empleados asociados.
 *
 * Esta clase usa el trait `HasFactory` para la creación de fábricas de modelos y permite la interacción con
 * la base de datos a través de Eloquent.
 */
class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'description',
        'phone_number',
    ];

    //
    public function employees() : HasMany
    {
        return $this->hasMany(Employee::class, 'company_id');
    }
    public $timestamps = true;
}
