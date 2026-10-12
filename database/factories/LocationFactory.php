<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Experimental Farm',
            'type' => $this->faker->randomElement(['farm', 'institute', 'custom_zone', 'custom_point']),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'parent_id' => null,
            'province_id' => null, 
            'city_id' => null,
        ];
    }
}
