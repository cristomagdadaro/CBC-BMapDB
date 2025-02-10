<?php

namespace Database\Factories\Location;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
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
            'regDesc' => $this->faker->city,
            'regDescLong' => $this->faker->unique()->numerify('####'),
            'country_id' => 1,
        ];
    }
}
