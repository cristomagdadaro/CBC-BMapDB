<?php

namespace Database\Factories;

use App\Models\Institute;
use App\Models\TWGExpert;
use App\Models\TWGService;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TWGService>
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
        $expert = TWGExpert::all()->random();
        $users = User::all();
        return [
            'user_id' => $users->random()->id,
            'institution' => Institute::all()->random()->id,
            'type' => $this->faker->randomElement(['service', 'training']),
            'purpose' => $this->faker->text(200),
            'direct_beneficiaries' => $this->faker->randomNumber(5),
            'indirect_beneficiaries' => $this->faker->randomNumber(5),
            'officer_in_charge' => $this->faker->name(),
            'cost' => $this->faker->randomFloat(2, 0, 1000000)
        ];
    }
}
