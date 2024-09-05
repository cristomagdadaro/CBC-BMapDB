<?php

namespace Database\Factories\Location;

use App\Models\Location\Province;
use App\Models\Location\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'cityDesc' => $this->faker->city,
            'regDesc' => Region::all()->random()->regDesc,
            'provDesc' => Province::all()->random()->provDesc,
        ];
    }
}
