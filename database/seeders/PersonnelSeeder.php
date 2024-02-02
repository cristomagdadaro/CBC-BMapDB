<?php

namespace Database\Seeders;

use App\Models\Inventory\Personnel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Personnel::factory()
            ->count(10)
            ->create();
    }
}
