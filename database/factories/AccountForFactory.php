<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountFor>
 */
class AccountForFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::inRandomOrder()->first(),
            'app_id' => \App\Models\Application::inRandomOrder()->first(),
            'account_id' => $this->faker->uuid(),
        ];
    }
}
