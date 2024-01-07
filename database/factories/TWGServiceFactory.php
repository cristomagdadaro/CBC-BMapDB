<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TWGService>
 */
class TWGServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['service', 'training']),
            'purpose' => $this->faker->text(200),
            'direct_beneficiaries' => $this->faker->randomNumber(5),
            'indirect_beneficiaries' => $this->faker->randomNumber(5),
            'officer_in_charge' => $this->faker->name(),
            'cost' => $this->faker->randomFloat(2, 0, 1000000)
        ];
    }
}
