<?php

namespace Database\Factories\Modules\PBMap\Models;

use App\Models\Institute;
use App\Models\Location\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PbMap\Models\Breeder;

class BreederFactory extends Factory
{
    protected $model = Breeder::class; // Define model for factory

    public function definition(): array
    {
        $institute = Institute::inRandomOrder()->first();
        $city = City::inRandomOrder()->first();

        return [
            'user_id' => User::inRandomOrder()->first()->id,  // Random user
            'fname' => $this->faker->firstName(),
            'mname' => $this->faker->randomElement([null, $this->faker->lastName()]),
            'lname' => $this->faker->lastName(),
            'suffix' => $this->faker->randomElement([null, 'Jr.', 'Sr.', 'II']),
            'affiliation' => $institute->id,  // Random affiliation
            'geolocation' => $city->id,  // Random city geolocation
            'mobile_no' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
        ];
    }
}
