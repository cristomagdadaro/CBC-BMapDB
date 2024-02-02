<?php

namespace Database\Factories\Inventory;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = \App\Models\Inventory\Category::all();
        return [
            'id' => $this->faker->uuid,
            'name' => $this->faker->word,
            'brand' => $this->faker->word,
            'description' => $this->faker->word,
            'category_id' => $categories->random()->id,
            'image' => $this->faker->word,
        ];
    }
}
