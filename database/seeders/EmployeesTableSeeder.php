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
            ['auth_id' => 16, 'company_id' => 1],
            ['auth_id' => 17, 'company_id' => 2],
            ['auth_id' => 18, 'company_id' => 3],
            ['auth_id' => 19, 'company_id' => 1],
            ['auth_id' => 20, 'company_id' => 2],
            ['auth_id' => 21, 'company_id' => 3],
        ]);
    }
}
