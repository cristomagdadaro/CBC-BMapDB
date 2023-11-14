<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commodity>
 */
class CommodityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ids = \App\Models\Breeder::pluck('id')->toArray();
        return [
            'name' => $this->faker->name,
            'breeder_id' => $this->faker->randomElement($ids),
            'scientific_name' => $this->faker->name,
            'variety' => $this->faker->name,
            'accession' => $this->faker->name,
            'germplasm' => $this->faker->name,
            'population' => $this->faker->randomFloat,
            'maturity_period' => $this->faker->name,
            'yield' => $this->faker->randomFloat,
            'description' => $this->faker->text,
            'image' => $this->faker->text,
        ];
    }
}
