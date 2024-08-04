<?php

namespace Database\Seeders;

use App\Models\TWGExpert;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TWGExpertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TWGExpert::factory()->count(rand(1, 100))->create();
    }
}
