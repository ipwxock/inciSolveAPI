<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


/**
 * Seeder para poblar la tabla `users` con datos de ejemplo.
 *
 * Este seeder inserta una lista de usuarios en la base de datos.
 * Se pueden agregar más registros modificando el arreglo pasado al método `insert`.
 *
 * Los campos insertados son:
 *
 * - `dni`: Número de identificación del usuario.
 * - `first_name`: Nombre del usuario.
 * - `last_name`: Apellido del usuario.
 * - `email`: Correo electrónico del usuario.
 * - `password`: Contraseña del usuario.
 * - `role`: Rol del usuario.
 * - `created_at`: Fecha y hora de creación de la entrada.
 * - `updated_at`: Fecha y hora de última actualización de la entrada.
 *
 * @return void
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'dni' => '12345678A',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'dni' => '87654321B',
                'first_name' => 'Jane',
                'last_name' => 'Smith', 'email' => 'jane.smith@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '11223344C',
                'first_name' => 'Alice',
                'last_name' => 'Brown',
                'email' => 'alice.brown@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'dni' => '44332211D',
                'first_name' => 'Bob',
                'last_name' => 'Johnson',
                'email' => 'bob.johnson@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '56789012E',
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'email' => 'emily.davis@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '67890123F',
                'first_name' => 'Charlie',
                'last_name' => 'Wilson',
                'email' => 'charlie.wilson@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '78901234G',
                'first_name' => 'Sophia',
                'last_name' => 'Miller',
                'email' => 'sophia.miller@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '89012345H',
                'first_name' => 'Liam',
                'last_name' => 'Anderson',
                'email' => 'liam.anderson@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '90123456I',
                'first_name' => 'Mia',
                'last_name' => 'Taylor',
                'email' => 'mia.taylor@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '01234567J',
                'first_name' => 'Noah',
                'last_name' => 'Martinez',
                'email' => 'noah.martinez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '23456789K',
                'first_name' => 'Ava',
                'last_name' => 'Hernandez',
                'email' => 'ava.hernandez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '34567890L',
                'first_name' => 'Oliver',
                'last_name' => 'Young',
                'email' => 'oliver.young@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '45678901M',
                'first_name' => 'Amelia',
                'last_name' => 'King',
                'email' => 'amelia.king@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '56789012N',
                'first_name' => 'Elijah',
                'last_name' => 'Wright',
                'email' => 'elijah.wright@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '67890123O',
                'first_name' => 'Harper',
                'last_name' => 'Lopez',
                'email' => 'harper.lopez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '78901234P',
                'first_name' => 'Benjamin',
                'last_name' => 'Scott',
                'email' => 'benjamin.scott@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '89012345Q',
                'first_name' => 'Evelyn',
                'last_name' => 'Green',
                'email' => 'evelyn.green@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '90123456R',
                'first_name' => 'James',
                'last_name' => 'Adams',
                'email' => 'james.adams@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '01234567S',
                'first_name' => 'Scarlett',
                'last_name' => 'Baker',
                'email' => 'scarlett.baker@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Empleado',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '23456789T',
                'first_name' => 'Logan',
                'last_name' => 'Gonzalez',
                'email' => 'logan.gonzalez@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Empleado',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '34567890U',
                'first_name' => 'Luna',
                'last_name' => 'Nelson',
                'email' => 'luna.nelson@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Empleado',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '45678901V',
                'first_name' => 'Lance',
                'last_name' => 'Noble',
                'email' => 'lance.noble@example.com',
                'password' => Hash::make('password123'),
                'role' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
