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
        ]);
    }
}
