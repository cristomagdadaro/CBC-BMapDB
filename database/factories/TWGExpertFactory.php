<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TWGExpert>
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
            'fname' => $this->faker->firstName(),
            'mname' => $this->faker->lastName(),
            'lname' => $this->faker->lastName(),
            'suffix' => $this->faker->suffix(),
            'position' => $this->faker->jobTitle(),
            'educ_level' => $this->faker->randomElement(["Bachelor's", "Master's", 'Doctoral']),
            'expertise' => $this->faker->randomElement(['Agriculture', 'Fishery', 'Livestock', 'Forestry', 'Microbiology', 'Botany', 'Biotechnology']),
            'email' => $this->faker->email(),
            'mobile_no' => $this->faker->phoneNumber(),
        ];
    }
}
