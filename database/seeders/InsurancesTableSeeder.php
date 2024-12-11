<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'employee_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject_type' => 'Hogar',
                'description' => 'Protección completa para tu hogar',
                'customer_id' => 1,
                'employee_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
