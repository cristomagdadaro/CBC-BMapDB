<?php

namespace Database\Factories;

use App\Models\Institute;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TwgDb\Models\TWGExpert;
use Modules\TwgDb\Models\TWGProduct;

/**
 * @extends Factory<TWGProduct>
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
        $users = User::all();
        return [
            'user_id' => $users->random()->id,
            'institution' => Institute::all()->random()->id,
            'name' => $this->faker->name,
            'brand' => $this->faker->name,
            'purpose' => $this->faker->name,
            'cost' => $this->faker->numberBetween(999999, 99999999),
        ];
    }
}
