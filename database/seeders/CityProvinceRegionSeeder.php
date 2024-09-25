<?php

namespace Database\Seeders;

use App\Models\Location\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CityProvinceRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            RegionSeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
        ]);
    }
}
