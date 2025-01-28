<?php

namespace Database\Factories\Location;

use App\Models\Location\Region;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class ProvinceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provDesc' => $this->faker->state,
            'regDesc' => Region::all()->random()->regCode,
        ];
    }
}
