<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TwgDb\Models\TWGProject;

class TWGProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TWGProject::factory()->count(rand(1, 100))->create();
    }
}
