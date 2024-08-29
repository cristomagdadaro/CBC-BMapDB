<?php

namespace Database\Seeders;

use App\Models\Location\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = config('regions');

        foreach ($regions as $region) {
            Region::factory()->create([
                'psgcCode' => $region['psgcCode'],
                'regDesc' => $region['regDesc'],
                'regCode' => $region['regCode'],
                'country_id' => 1
            ]);
        }
    }
}
