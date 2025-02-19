<?php

namespace Database\Seeders;

use Modules\PbMap\Models\Breeder;
use Illuminate\Database\Seeder;

class BreederSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Breeder::factory()->count(100)->create();
    }
}
