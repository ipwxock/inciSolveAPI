<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para poblar la tabla `issues` con datos de ejemplo.
 *
 * Este seeder inserta una lista de incidencias en la base de datos.
 * Se pueden agregar más registros modificando el arreglo pasado al método `insert`.
 *
 * Los campos insertados son:
 *
 * - `insurance_id`: ID de la póliza de seguro asociada a la incidencia.
 * - `subject`: Asunto de la incidencia.
 * - `status`: Estado actual de la incidencia.
 * - `created_at`: Fecha y hora de creación de la entrada.
 * - `updated_at`: Fecha y hora de última actualización de la entrada.
 *
 * @return void
 */
class IssuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('issues')->insert([
            [
                'insurance_id' => 1,
                'subject' => 'Reclamación por daños en el hogar',
                'status' => 'Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 2,
                'subject' => 'Actualización de póliza de vida',
                'status' => 'Abierta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 3,
                'subject' => 'Consulta sobre cobertura de seguro de coche',
                'status' => 'Cerrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 4,
                'subject' => 'Reclamación por robo de coche',
                'status' => 'Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 5,
                'subject' => 'Consulta sobre cobertura de seguro de vida',
                'status' => 'Abierta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 6,
                'subject' => 'Reclamación por daños en el hogar',
                'status' => 'Cerrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 7,
                'subject' => 'Consulta sobre cobertura de seguro de coche',
                'status' => 'Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 8,
                'subject' => 'Reclamación por robo de coche',
                'status' => 'Abierta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 9,
                'subject' => 'Consulta sobre cobertura de seguro de vida',
                'status' => 'Cerrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 10,
                'subject' => 'Reclamación por daños en el hogar',
                'status' => 'Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 11,
                'subject' => 'Actualización de póliza de vida',
                'status' => 'Abierta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 12,
                'subject' => 'Consulta sobre cobertura de seguro de coche',
                'status' => 'Cerrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 13,
                'subject' => 'Reclamación por robo de coche',
                'status' => 'Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 14,
                'subject' => 'Consulta sobre cobertura de seguro de vida',
                'status' => 'Abierta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 15,
                'subject' => 'Reclamación por daños en el hogar',
                'status' => 'Cerrada',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
