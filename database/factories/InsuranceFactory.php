<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\User;
use \App\Models\Customer;
use \App\Models\Employee;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Insurance>
 */
class InsuranceFactory extends Factory
{
    /**
     * Define el estado del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomNumber(),
            'subject_type' => $this->faker->randomElement(['Vida','Robo','Defunción','Accidente','Incendios','Asistencia_carretera','Salud','Hogar','Auto','Viaje','Mascotas','Otros']),
            'description' => $this->faker->paragraph,
            'customer_id' => Customer::factory()->create()->id,
            'employee_id' => Employee::factory()->create()->id,
        ];
    }

}
