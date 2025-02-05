<?php

namespace Database\Factories;

use App\Models\Institute;
use App\Models\TWGExpert;
use App\Models\User;
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
        $users = User::all();
        return [
            'user_id' => $users->random()->id,
            'name' => $this->faker->name(),
            'position' => $this->faker->jobTitle(),
            'institution' => Institute::all()->random()->id,
            'educ_level' => $this->faker->randomElement(["Bachelor's", "Master's", 'Doctoral']),
            'expertise' => $this->faker->randomElement(['Agriculture', 'Fishery', 'Livestock', 'Forestry', 'Microbiology', 'Botany', 'Biotechnology']),
            'research_interest' => $this->faker->randomElement(['Agriculture', 'Fishery', 'Livestock', 'Forestry', 'Microbiology', 'Botany', 'Biotechnology']),
            'mobile' => '09' . $this->faker->randomNumber(9, true),
            'email' => $this->faker->unique()->safeEmail(),
            ];
    }
}
