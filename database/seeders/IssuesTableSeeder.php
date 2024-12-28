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
            [
                'insurance_id' => 3,
                'subject' => 'Consulta sobre cobertura de seguro de auto',
                'status' => 'Cerrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 4,
                'subject' => 'Reclamación por robo de auto',
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
                'subject' => 'Consulta sobre cobertura de seguro de auto',
                'status' => 'Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 8,
                'subject' => 'Reclamación por robo de auto',
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
                'subject' => 'Consulta sobre cobertura de seguro de auto',
                'status' => 'Cerrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'insurance_id' => 13,
                'subject' => 'Reclamación por robo de auto',
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
