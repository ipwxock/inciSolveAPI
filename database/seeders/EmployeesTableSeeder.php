<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            ['auth_id' => 2, 'company_id' => 1,'created_at' => now(),
            'updated_at' => now(),],
            ['auth_id' => 3, 'company_id' => 2,'created_at' => now(),
            'updated_at' => now(),],
        ]);
    }
}
