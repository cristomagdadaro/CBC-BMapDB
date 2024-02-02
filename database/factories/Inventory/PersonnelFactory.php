<?php

namespace Database\Factories\Inventory;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory\Personnel>
 */
class PersonnelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fname' => $this->faker->name,
            'mname' => $this->faker->randomElement([null, $this->faker->name]),
            'lname' => $this->faker->lastName,
            'suffix' => $this->faker->randomElement([null, 'Jr', null, 'Sr', null, 'II', null, 'III', null, 'IV']),
            'position' => $this->faker->word,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'email' => $this->faker->email,
        ];
    }
}
