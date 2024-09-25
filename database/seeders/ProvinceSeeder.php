<?php

namespace Database\Seeders;

use App\Models\Location\Province;
use App\Models\Location\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = config('provinces');

        foreach ($provinces as $province) {
            Province::factory()->create([
                'provDesc' => trim($province['provDesc']),
                'regDesc' => trim($province['regDesc']),
            ]);
        }

    }
}
