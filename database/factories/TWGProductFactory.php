<?php

namespace Database\Factories;

use App\Models\TWGExpert;
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
        $expert = TWGExpert::all()->random();
        return [
            'twg_expert_id' => $expert->id,
            'name' => $this->faker->name,
            'brand' => $this->faker->name,
            'purpose' => $this->faker->name,
            'cost' => $this->faker->numberBetween(999999, 99999999),
        ];
    }
}
