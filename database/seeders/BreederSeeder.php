<?php

namespace Database\Seeders;

use App\Models\Breeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BreederSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Breeder::factory()->count(15)->create();
    }
}