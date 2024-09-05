<?php

namespace Database\Factories;

use App\Models\Location\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
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
        $city = City::all()->random();
        $province = $city->province;
        $region = $province->region;
        return [
            'user_id' => User::all()->random()->id,
            'name' => $this->faker->name(),
            'agency' => $this->faker->company(),
            'city' => $city->cityDesc,
            'province' => $province->provDesc,
            'region' => $region->regDesc,
            'country' => 'Philippines',
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
