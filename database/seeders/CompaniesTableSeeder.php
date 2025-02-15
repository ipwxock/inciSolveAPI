<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

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
