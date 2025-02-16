<?php

namespace Database\Factories;

use App\Models\Breeder;
use App\Models\Geodata;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Geodata>
 */
class GeodataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $breeders = Breeder::pluck('id')->toArray();


        return [
            'breeder_id' => $this->faker->randomElement($breeders),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'province' => $this->faker->state,
            'region' => $this->faker->state,
            'country' => $this->faker->country,
            'postal_code' => $this->faker->postcode,
            'formatted_address' => $this->faker->address,
            'place_id' => $this->faker->uuid,
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
