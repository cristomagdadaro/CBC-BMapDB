<?php

namespace Database\Factories;

use App\Models\TWGExpert;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TWGExpert>
 */
class TWGExpertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->unique()->numberBetween(1, 10),
            'position' => $this->faker->jobTitle(),
            'educ_level' => $this->faker->randomElement(["Bachelor's", "Master's", 'Doctoral']),
            'expertise' => $this->faker->randomElement(['Agriculture', 'Fishery', 'Livestock', 'Forestry', 'Microbiology', 'Botany', 'Biotechnology']),
        ];
    }
}