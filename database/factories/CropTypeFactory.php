<?php

namespace Database\Factories;

use App\Models\CropType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CropTypeFactory extends Factory
{
    protected $model = CropType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Rice', 'Corn', 'Abaca', 'Banana', 'Mango']),
            'scientific_name' => $this->faker->words(2, true),
            'icon_url' => $this->faker->imageUrl(),
            'attributes_schema' => [
                'color' => 'string',
                'yield_potential' => 'number',
                'maturity_days' => 'number'
            ],
        ];
    }
}
