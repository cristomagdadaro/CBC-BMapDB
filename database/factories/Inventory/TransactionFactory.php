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
        $items = \App\Models\Inventory\Item::all();
        $suppliers = \App\Models\Inventory\Supplier::all();
        $personnel = \App\Models\Inventory\Personnel::all();

        return [
            'id' => $this->faker->uuid,
            'item_id' => $items->random(null, true)->id,
            'barcode' => "CBC-{$this->faker->randomElement(['01','02','03','04','05'])}-{$this->faker->randomNumber(5, true)}",
            'unit_price' => $this->faker->word,
            'unit' => $this->faker->randomElement(['kg','pcs','lbs','g','mg','mL','L']),
            'quantity' => $this->faker->randomNumber(),
            'total_cost' => $this->faker->randomFloat(2, 0, 999999.99),
            'transac_type' => $this->faker->randomElement(['in', 'out']),
            'personnel_id' => $personnel->random()->id,
            'supplier_id' => $suppliers->random()->id,
            'expiration' => $this->faker->date(),
            'remarks' => $this->faker->word,
        ];
    }
}
