<?php

namespace Database\Seeders;

use App\Models\Location\City;
use App\Models\Location\Province;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ph_cities =  config('cities');
        foreach ($ph_cities as $city) {
            City::factory()->create(
                [
                    'latitude' => trim($city['latitude']),
                    'longitude' => trim($city['longitude']),
                    'cityDesc' => $city['cityDesc'],
                    'regDesc' => $city['regDesc'],
                    'provDesc' => $city['provDesc'],
                ]
            );
        }
    }
}
