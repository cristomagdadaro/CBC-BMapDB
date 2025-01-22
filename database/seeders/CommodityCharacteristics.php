<?php

namespace Database\Seeders;

use App\Models\CommodityCharacteristic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommodityCharacteristics extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommodityCharacteristic::factory()->count(100)->create();
    }
}
