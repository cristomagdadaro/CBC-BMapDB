<?php

namespace Database\Seeders;

use App\Models\Geodata;
use Illuminate\Database\Seeder;
use Modules\PbMap\Models\Breeder;

class GeodataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Breeder::all()->count()){
            Breeder::all()->each(function ($breeder) {
                Geodata::factory()->create([
                    'breeder_id' => $breeder->id,
                ]);
            });
        }
    }
}
