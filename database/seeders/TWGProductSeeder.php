<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\TwgDb\Models\TWGProduct;

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
