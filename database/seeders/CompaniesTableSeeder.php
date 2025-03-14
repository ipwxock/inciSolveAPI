<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

/**
 * Seeder para poblar la tabla `companies` con datos de ejemplo.
 *
 * Este seeder inserta una lista de empresas en la base de datos.
 * Se pueden agregar más registros modificando el arreglo pasado al método `insert`.
 *
 * Los campos insertados son:
 *
 * - `name`: El nombre de la empresa.
 * - `description`: Descripción breve de la empresa.
 * - `phone_number`: Número de teléfono de contacto de la empresa.
 * - `created_at`: Fecha y hora de creación de la entrada.
 * - `updated_at`: Fecha y hora de última actualización de la entrada.
 *
 * @return void
 */
class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            ['name' => 'Seguros Global',
             'description' => 'Líder en seguros para todo tipo de necesidades',
             'phone_number' => '123456789',
             'created_at' => now(),
            'updated_at' => now(),
            ],

            ['name' => 'AseguraTuHogar',
             'description' => 'Especialistas en seguros para el hogar y familia',
             'phone_number' => '456789123',
             'created_at' => now(),
            'updated_at' => now(),
            ],
            ['name' => 'Seguros de Vida',
             'description' => 'Protección para toda la familia',
             'phone_number' => '987234567',
             'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
