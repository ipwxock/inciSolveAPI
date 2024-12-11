<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['dni' => '12345678A', 'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john.doe@example.com', 'password' => Hash::make('password123'), 'role' => 'Cliente','created_at' => now(),
                'updated_at' => now(),],
            ['dni' => '87654321B', 'first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane.smith@example.com', 'password' => Hash::make('password123'), 'role' => 'Empleado','created_at' => now(),
                'updated_at' => now(),],
            ['dni' => '11223344C', 'first_name' => 'Alice', 'last_name' => 'Brown', 'email' => 'alice.brown@example.com', 'password' => Hash::make('password123'), 'role' => 'Manager','created_at' => now(),
                'updated_at' => now(),],
            ['dni' => '44332211D', 'first_name' => 'Bob', 'last_name' => 'Johnson', 'email' => 'bob.johnson@example.com', 'password' => Hash::make('password123'), 'role' => 'Admin','created_at' => now(),
                'updated_at' => now(),],
        ]);
    }
}
