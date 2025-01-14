<?php

namespace Database\Seeders;

use App\Models\TWGService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
