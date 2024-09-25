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
                'regDesc' => trim($region['regDesc']),
                'regDescLong' => trim($region['regDescLong']),
                'country_id' => 1
            ]);
        }
    }
}
