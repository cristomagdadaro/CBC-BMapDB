<?php

namespace Database\Seeders;

use App\Models\TWGProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TWGProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TWGProduct::factory()->count(rand(1, 100))->create();
    }
}
