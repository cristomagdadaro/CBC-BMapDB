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
            /*if ($temp >= 300)
                break;*/

            $cityDesc = trim((string) $city['cityDesc']);
            $provDesc = trim((string) $city['provDesc']);
            $regDesc = trim((string) $city['regDesc']);

            City::updateOrCreate(
                [
                    'cityDesc' => $cityDesc,
                    'provDesc' => $provDesc,
                    'regDesc' => $regDesc,
                ],
                [
                    'latitude' => trim($city['latitude']),
                    'longitude' => trim($city['longitude']),
                ]
            );
        }
    }
}
