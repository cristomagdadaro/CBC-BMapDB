<?php

namespace Database\Factories;

use App\Models\TWGProject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TWGProject>
 */
class TWGProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'twg_expert_id' => $this->faker->numberBetween(1, 10),
            'title' => $this->faker->sentence(),
            'objective' => $this->faker->paragraph(),
            'expected_output' => $this->faker->sentence(),
            'project_leader' => $this->faker->name(),
            'funding_agency' => $this->faker->randomElement(['DOST', 'PCA-RCAARRD','IRRI','UPLB','VSU','BFAR']),
            'duration' => $this->faker->randomElement(['1 year', '2 years', '3 years', '4 years', '5 years']),
            'status' => $this->faker->randomElement(['Active', 'Completed', 'Cancelled', 'On Hold'])
        ];
    }
}