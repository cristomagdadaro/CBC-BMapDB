<?php

namespace Database\Factories;

use App\Models\Institute;
use App\Models\Location\City;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Model>
 */
class BreederFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $institute = Institute::all()->random();
        $city = City::all()->random();
        return [
            'user_id' => User::all()->random()->id,
            'fname' => $this->faker->firstName(),
            'mname' => $this->faker->randomElement([null,$this->faker->lastName(),null,null,null,null]),
            'lname' => $this->faker->lastName(),
            'suffix' => $this->faker->randomElement([null,null,null,'Jr.',null,'Sr.',null,null,null,'I',null,null,'II',null,null,'III',null,null,null,null,'IV']),
            'affiliation' => $institute->id,
            'geolocation' => $city->id,
            'mobile_no' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
