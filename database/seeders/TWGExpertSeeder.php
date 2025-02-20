<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TwgDb\Models\TWGExpert;

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
