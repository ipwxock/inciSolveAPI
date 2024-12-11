<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        ]);
    }
}
