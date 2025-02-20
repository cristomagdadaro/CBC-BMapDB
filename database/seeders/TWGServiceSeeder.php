<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TwgDb\Models\TWGService;

class TWGServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TWGService::factory()
            ->count(rand(1, 100))
            ->create();
    }
}
