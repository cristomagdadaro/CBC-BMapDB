<?php

namespace Database\Seeders;

use App\Models\CommodityInfo;
use Illuminate\Database\Seeder;

class CommodityAdditionalInfo extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CommodityInfo::factory()->count(100)->create();
    }
}
