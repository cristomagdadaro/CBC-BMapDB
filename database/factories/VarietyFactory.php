<?php

namespace Database\Factories;

use App\Models\Variety;
use App\Models\CropType;
use App\Models\Institute;
use Illuminate\Database\Eloquent\Factories\Factory;

class VarietyFactory extends Factory
{
    protected $model = Variety::class;

    public function definition(): array
    {
        return [
            'crop_type_id' => CropType::factory(),
            'institute_id' => Institute::factory(),
            'name' => 'NSIC ' . $this->faker->year() . ' ' . $this->faker->word,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'attributes' => [
                'color' => $this->faker->colorName,
                'yield_potential' => $this->faker->randomFloat(2, 2, 10),
                'maturity_days' => $this->faker->numberBetween(90, 150)
            ],
            'approved_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
