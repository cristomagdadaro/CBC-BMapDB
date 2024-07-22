<?php

namespace Database\Factories;

use Faker\Core\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accounts>
 */
class AccountsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'user_id' => \App\Models\User::inRandomOrder()->first(),
            'app_id' => \App\Models\Application::inRandomOrder()->first(),
            'approved_at' => $this->faker->randomElement([null, $this->faker->dateTime()]),
        ];
    }
}
