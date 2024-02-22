<?php

namespace Database\Seeders;

use App\Models\Inventory\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            InventoryCategorySeeder::class,
            ItemsSeeder::class,
            SupplierSeeder::class,
            PersonnelSeeder::class,
            //TransactionSeeder::class,
        ]);
    }
}
