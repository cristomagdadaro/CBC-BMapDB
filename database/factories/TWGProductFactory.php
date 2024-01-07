<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TWGProduct>
 */
class TWGProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'brand' => $this->faker->name,
            'purpose' => $this->faker->name,
            'cost' => $this->faker->numberBetween(9999, 999999),
        ];
    }
}
