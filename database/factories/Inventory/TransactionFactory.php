<?php

namespace Database\Factories\Inventory;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'transac_type' => $this->faker->word,
            'quantity' => $this->faker->randomNumber(),
            'unit' => $this->faker->word,
            'cost' => $this->faker->randomFloat(2, 0, 999999.99),
            'supplier_id' => $this->faker->uuid,
            'remarks' => $this->faker->word,
            'status' => $this->faker->word,
        ];
    }
}
