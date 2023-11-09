<?php

namespace Database\Seeders;

use App\Models\Breeder;
use App\Models\Geodata;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
