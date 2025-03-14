<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para poblar la tabla `customers` con datos de ejemplo.
 *
 * Este seeder inserta una lista de clientes en la base de datos.
 * Se pueden agregar más registros modificando el arreglo pasado al método `insert`.
 *
 * Los campos insertados son:
 *
 * - `auth_id`: ID del usuario autenticado asociado al cliente.
 * - `phone_number`: Número de teléfono de contacto del cliente.
 * - `address`: Dirección de residencia del cliente.
 * - `created_at`: Fecha y hora de creación de la entrada.
 * - `updated_at`: Fecha y hora de última actualización de la entrada.
 *
 * @return void
 */
class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            ['auth_id' => 1, 'phone_number' => '123456789', 'address' => 'Calle Mayor 1, Madrid'],

            ['auth_id' => 2, 'phone_number' => '987654321', 'address' => 'Calle Principal 2, Barcelona'],

            ['auth_id' => 3, 'phone_number' => '987654321', 'address' => 'Calle Hertzel 5, Sevilla'],
            [
                'auth_id' => 4,
                'phone_number' => '987654321',
                'address' => 'Calle Zevrup 12, Almería',

            ],
            [
                'auth_id' => 5,
                'phone_number' => '987654321',
                'address' => 'Calle Zevrup 12, Almería',

            ],
            [
                'auth_id' => 6,
                'phone_number' => '987654321',
                'address' => 'Calle Gataloca 23, Cuenca',

            ],
            [
                'auth_id' => 7,
                'phone_number' => '987654321',
                'address' => 'Calle Mapache 365, Zaragoza',

            ],
            [
                'auth_id' => 8,
                'phone_number' => '987654321',
                'address' => 'Calle MIMO s/n, Valencia',

            ],
            [
                'auth_id' => 9,
                'phone_number' => '987654321',
                'address' => 'Calle Gatúnida 88, Palma de Mallorca',

            ],
            [
                'auth_id' => 10,
                'phone_number' => '987654321',
                'address' => 'Calle Orange 12, Oviedo',

            ],
            [
                'auth_id' => 11,
                'phone_number' => '987654321',
                'address' => 'Calle Becerrillo 23, Albacete',

            ],
            [
                'auth_id' => 12,
                'phone_number' => '987654321',
                'address' => 'Calle Trigger 15, Oslo',

            ],
            [
                'auth_id' => 13,
                'phone_number' => '987654321',
                'address' => 'Calle Kentax 37, Málaga',

            ],
            [
                'auth_id' => 14,
                'phone_number' => '987654321',
                'address' => 'Calle Madclone 2, Granada',

            ],
            [
                'auth_id' => 15,
                'phone_number' => '987654321',
                'address' => 'Calle philoxg 1, Jaén',

            ],



        ]);

    }
}
