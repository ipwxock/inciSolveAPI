<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
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

    public function customer()
    {
        return $this->state(fn () => ['role' => 'Cliente']);
    }

    public function employee()
    {
        return $this->state(fn () => ['role' => 'Empleado']);
    }

    public function admin()
    {
        return $this->state(fn () => ['role' => 'Admin']);
    }

    public function manager()
    {
        return $this->state(fn () => ['role' => 'Manager']);
    }
}
