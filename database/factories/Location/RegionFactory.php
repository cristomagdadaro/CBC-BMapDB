<?php

namespace Database\Factories\Location;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RegionFactory extends Factory
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
            'regDesc' => $this->faker->city,
            'regCode' => $this->faker->unique()->numerify('####'),
            'country_id' => 1,
        ];
    }
}
