<?php

namespace Database\Factories\Location;

use App\Models\Location\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
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
            'psgcCode' => $this->faker->unique()->numerify('####'),
            'provDesc' => $this->faker->state,
            'regCode' => Region::all()->random()->regCode,
            'provCode' => $this->faker->unique()->numerify('##'),
        ];
    }
}
