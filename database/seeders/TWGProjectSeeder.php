<?php

namespace Database\Seeders;

use App\Models\TWGProject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
