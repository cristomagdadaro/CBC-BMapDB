<?php

namespace Database\Factories;

use App\Models\Institute;
use App\Models\TWGExpert;
use App\Models\TWGProject;
use App\Models\User;
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
        $users = User::all();
        return [
            'user_id' => $users->random()->id,
            'institution' => Institute::all()->random()->id,
            'title' => $this->faker->sentence(),
            'objective' => $this->faker->paragraph(),
            'expected_output' => $this->faker->sentence(),
            'project_leader' => TWGExpert::all()->random()->first()->id,
            'funding_agency' => $this->faker->randomElement(['DOST', 'PCA-RCAARRD','IRRI','UPLB','VSU','BFAR']),
            'duration' => $this->faker->randomElement(['1 year', '2 years', '3 years', '4 years', '5 years']),
            'status' => $this->faker->randomElement(['Active', 'Completed', 'Cancelled', 'On Hold'])
        ];
    }
}
