<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Insurance;

/**
 * Clase que representa una incidencia asociada a una póliza de seguro.
 *
 * Esta clase gestiona la información relacionada con las incidencias de las pólizas, incluyendo el tipo de incidencia
 * (asunto) y su estado actual.
 *
 * Propiedades:
 * - insurance_id (integer): El identificador de la póliza de seguro asociada a la incidencia.
 * - subject (string): El asunto o descripción breve de la incidencia.
 * - status (string): El estado actual de la incidencia (ej. 'abierta', 'cerrada', etc.).
 *
 * Relaciones:
 * - insurance(): Establece una relación de "pertenencia" con el modelo `Insurance`, indicando que una incidencia
 *   está asociada a una póliza específica.
 *
 * Esta clase utiliza el trait `HasFactory` para facilitar la creación de fábricas de modelos y la interacción con la
 * base de datos mediante Eloquent. Los registros de esta clase gestionan los timestamps de creación/actualización
 * debido a que `timestamps` está configurado como `true`.
 */
class Issue extends Model
{
    use HasFactory;

    protected $table = 'issues';
    protected $fillable = ['insurance_id', 'subject', 'status'];

    public function insurance()
    {
        return $this->belongsTo(Insurance::class,'insurance_id');
    }

    public $timestamps = true;
}
