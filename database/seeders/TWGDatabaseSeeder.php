<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TWGDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            TWGExpertSeeder::class,
            TWGProjectSeeder::class,
            TWGProductSeeder::class,
            TWGServiceSeeder::class,
        ]);
    }
}
