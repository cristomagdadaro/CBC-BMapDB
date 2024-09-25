<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Institute>
 */
class InstituteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $institutes = config('list_of_identified_biotech_intitutes');
        $randomInstitute = $this->faker->randomElement($institutes);

        return [
            'name' => $randomInstitute['name'],
            'inst_type' => $randomInstitute['inst_type'],
            'city' => $randomInstitute['city'],
            'province' => $randomInstitute['province'],
            'region' => $randomInstitute['region'],
            'website' => $randomInstitute['website'],
            'email' => $randomInstitute['email'],
            'phone' => $randomInstitute['phone'],
        ];
    }
}
