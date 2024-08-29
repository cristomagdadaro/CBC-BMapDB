<?php

namespace Database\Seeders;

use App\Models\Location\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::factory()->create([
            'country' => 'Philippines',
            'iso_code' => 'PH',
        ]);
    }
}
