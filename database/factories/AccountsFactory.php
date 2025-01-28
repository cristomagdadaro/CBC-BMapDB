<?php

namespace Database\Factories;

use App\Models\Accounts;
use App\Models\Application;
use App\Models\User;
use Faker\Core\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Accounts>
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
            'user_id' => User::inRandomOrder()->first(),
            'app_id' => Application::inRandomOrder()->first(),
            'approved_at' => $this->faker->randomElement([null, $this->faker->dateTime()]),
        ];
    }
}
