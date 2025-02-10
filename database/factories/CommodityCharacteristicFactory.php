<?php

namespace Database\Factories;

use App\Models\Commodity;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class CommodityCharacteristicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commodity_id' => Commodity::inRandomOrder()->first()->id,
            'weight' => $this->faker->randomFloat(2, 0, 100),
            'length' => $this->faker->randomFloat(2, 0, 100),
            'width' => $this->faker->randomFloat(2, 0, 100),
            'shape' => $this->faker->randomElement(['round', 'oval', 'square', 'rectangular']),

            'skin_color' => $this->faker->randomElement(['red', 'green', 'yellow', 'purple', 'orange', 'brown', 'black', 'white']),
            'skin_texture' => $this->faker->randomElement(['smooth', 'rough', 'bumpy', 'spiky', 'hairy']),
            'flesh_color' => $this->faker->randomElement(['red', 'green', 'yellow', 'purple', 'orange', 'brown', 'black', 'white']),
            'flesh_texture' => $this->faker->randomElement(['soft', 'hard', 'crunchy', 'mushy', 'fibrous']),
            'flesh_flavor' => $this->faker->randomElement(['sweet', 'sour', 'bitter', 'salty', 'umami']),
            'aroma' => $this->faker->randomElement(['none', 'fruity', 'floral', 'spicy', 'musky', 'earthy']),

            'root_flesh_color' => $this->faker->randomElement(['red', 'green', 'yellow', 'purple', 'orange', 'brown', 'black', 'white']),
            'root_cortex_color' => $this->faker->randomElement(['red', 'green', 'yellow', 'purple', 'orange', 'brown', 'black', 'white']),
            'root_skin_color' => $this->faker->randomElement(['red', 'green', 'yellow', 'purple', 'orange', 'brown', 'black', 'white']),
            'root_shape' => $this->faker->randomElement(['round', 'oval', 'square', 'rectangular']),

            'tuber_flesh_color' => $this->faker->randomElement(['red', 'green', 'yellow', 'purple', 'orange', 'brown', 'black', 'white']),
            'tuber_cortex_color' => $this->faker->randomElement(['red', 'green', 'yellow', 'purple', 'orange', 'brown', 'black', 'white']),
            'tuber_skin_color' => $this->faker->randomElement(['red', 'green', 'yellow', 'purple', 'orange', 'brown', 'black', 'white']),
            'tuber_shape' => $this->faker->randomElement(['round', 'oval', 'square', 'rectangular']),
        ];
    }
}
