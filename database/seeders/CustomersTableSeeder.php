<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            ['auth_id' => 1, 'phone_number' => '+34123456789', 'address' => 'Calle Mayor 1, Madrid','created_at' => now(),
                'updated_at' => now(),],

            ['auth_id' => 2, 'phone_number' => '+34987654321', 'address' => 'Calle Principal 2, Barcelona','created_at' => now(),
                'updated_at' => now(),],

            ['auth_id' => 3, 'phone_number' => '+34987654321', 'address' => 'Calle Hertzel 5, Sevilla','created_at' => now(),
                'updated_at' => now(),],
            [
                'auth_id' => 4,
                'phone_number' => '+34987654321',
                'address' => 'Calle Zevrup 12, Almería',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 5,
                'phone_number' => '+34987654321',
                'address' => 'Calle Zevrup 12, Almería',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 6,
                'phone_number' => '+34987654321',
                'address' => 'Calle Gataloca 23, Cuenca',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 7,
                'phone_number' => '+34987654321',
                'address' => 'Calle Mapache 365, Zaragoza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 8,
                'phone_number' => '+34987654321',
                'address' => 'Calle MIMO s/n, Valencia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 9,
                'phone_number' => '+34987654321',
                'address' => 'Calle Gatúnida 88, Palma de Mallorca',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 10,
                'phone_number' => '+34987654321',
                'address' => 'Calle Orange 12, Oviedo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 11,
                'phone_number' => '+34987654321',
                'address' => 'Calle Becerrillo 23, Albacete',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 12,
                'phone_number' => '+34987654321',
                'address' => 'Calle Trigger 15, Oslo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 13,
                'phone_number' => '+34987654321',
                'address' => 'Calle Kentax 37, Málaga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 14,
                'phone_number' => '+34987654321',
                'address' => 'Calle Madclone 2, Granada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'auth_id' => 15,
                'phone_number' => '+34987654321',
                'address' => 'Calle philoxg 1, Jaén',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        

        ]);

    }
}
