<?php

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\Location\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutes = config('list_of_identified_biotech_intitutes');

        foreach ($institutes as $institute) {
            $city =  City::select()->where('cityDesc', $institute['city'])->first();

            if ($city)
                $city = City::all()->random();

            if (!$city)
                $city = City::all()->first();

            Institute::create([
                'name' => $institute['name'],
                'inst_type' => $institute['inst_type'],
                'geolocation' => $city->id,
                'website' => $institute['website'],
                'email' => $institute['email'],
                'phone' => $institute['phone'],
            ]);
        }
    }
}
