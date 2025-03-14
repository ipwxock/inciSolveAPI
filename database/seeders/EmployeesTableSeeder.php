<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para poblar la tabla `employees` con datos de ejemplo.
 *
 * Este seeder inserta una lista de empleados en la base de datos.
 * Se pueden agregar más registros modificando el arreglo pasado al método `insert`.
 *
 * Los campos insertados son:
 *
 * - `auth_id`: ID del usuario autenticado asociado al empleado.
 * - `company_id`: ID de la empresa a la que pertenece el empleado.
 * - `created_at`: Fecha y hora de creación de la entrada.
 * - `updated_at`: Fecha y hora de última actualización de la entrada.
 *
 * @return void
 */
class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            ['auth_id' => 16, 'company_id' => 1],
            ['auth_id' => 17, 'company_id' => 2],
            ['auth_id' => 18, 'company_id' => 3],
            ['auth_id' => 19, 'company_id' => 1],
            ['auth_id' => 20, 'company_id' => 2],
            ['auth_id' => 21, 'company_id' => 3],
        ]);
    }
}
