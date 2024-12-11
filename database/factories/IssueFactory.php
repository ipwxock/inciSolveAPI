<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\Insurance;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->randomNumber(),
            'insurance_id' => Insurance::factory(),
            'subject' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['Abierta', 'Cerrada', 'Pendiente']),
        ];
    }
}
