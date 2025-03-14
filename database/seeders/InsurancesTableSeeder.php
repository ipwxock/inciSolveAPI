<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para poblar la tabla `insurances` con datos de ejemplo.
 *
 * Este seeder inserta una lista de pólizas en la base de datos.
 * Se pueden agregar más registros modificando el arreglo pasado al método `insert`.
 *
 * Los campos insertados son:
 *
 * - `subject_type`: Tipo de seguro.
 * - `description`: Descripción de la póliza.
 * - `customer_id`: ID del cliente asociado a la póliza.
 * - `employee_id`: ID del empleado asociado a la póliza.
 * - `created_at`: Fecha y hora de creación de la entrada.
 * - `updated_at`: Fecha y hora de última actualización de la entrada.
 *
 * @return void
 */
class InsurancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('insurances')->insert([
            [
                'subject_type' => 'Vida',
                'description' => 'Seguro de vida para toda la familia',
                'customer_id' => 1,
                'employee_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Hogar',
                'description' => 'Protección completa para tu hogar',
                'customer_id' => 1,
                'employee_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Salud',
                'description' => 'Cobertura médica para toda la familia',
                'customer_id' => 2,
                'employee_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Vida',
                'description' => 'Seguro de hertzel para toda la familia',
                'customer_id' => 3,
                'employee_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Accidente',
                'description' => 'Protección completa para tu seguridad',
                'customer_id' => 3,
                'employee_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Defunción',
                'description' => 'Asegura tu tranquilidad',
                'customer_id' => 4,
                'employee_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Viaje',
                'description' => 'Viaja seguro con nosotros',
                'customer_id' => 5,
                'employee_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Coche',
                'description' => 'Protección completa para tu coche',
                'customer_id' => 5,
                'employee_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Moto',
                'description' => 'Protección completa para tu moto',
                'customer_id' => 6,
                'employee_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Robo',
                'description' => 'Protección completa contra robos',
                'customer_id' => 7,
                'employee_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Incendios',
                'description' => 'Protección completa contra incendios',
                'customer_id' => 8,
                'employee_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Asistencia_carretera',
                'description' => 'Asistencia en carretera para tu coche',
                'customer_id' => 9,
                'employee_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Mascotas',
                'description' => 'Protección completa para tu mascota',
                'customer_id' => 10,
                'employee_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Otros',
                'description' => 'Seguro personalizado',
                'customer_id' => 11,
                'employee_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Hogar',
                'description' => 'Protección completa para tu hogar',
                'customer_id' => 12,
                'employee_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Salud',
                'description' => 'Cobertura médica para toda la familia',
                'customer_id' => 13,
                'employee_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Vida',
                'description' => 'Seguro de vida para toda la familia',
                'customer_id' => 14,
                'employee_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Hogar',
                'description' => 'Protección completa para tu hogar',
                'customer_id' => 15,
                'employee_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
