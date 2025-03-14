<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define el estado del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'dni' => $this->faker->unique()->regexify('[0-9]{8}[A-Za-z]'),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
            'role' => $this->faker->randomElement(['Admin', 'Empleado', 'Cliente']),
        ];
    }

    /**
     * Crea un usuario con el rol de cliente.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function customer()
    {
        return $this->state(fn () => ['role' => 'Cliente']);
    }

    /**
     * Crea un usuario con el rol de empleado.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function employee()
    {
        return $this->state(fn () => ['role' => 'Empleado']);
    }

    /**
     * Crea un usuario con el rol de administrador.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(fn () => ['role' => 'Admin']);
    }

    /**
     * Crea un usuario con el rol de manager.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function manager()
    {
        return $this->state(fn () => ['role' => 'Manager']);
    }
}
