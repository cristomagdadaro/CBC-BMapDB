<?php

namespace Database\Seeders;

use App\Models\Breeder;
use Illuminate\Database\Seeder;

class BreedersMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Breeder::factory()->count(50)->create()->each(function ($breeder) {
            $userId = \App\Models\User::inRandomOrder()->first()->id;
            $breeder->update(['user_id' => $userId]);

            $breeder->commodities()->createMany(
                \App\Models\Commodity::factory()->count(rand(1, 15))->make([
                    'user_id' => $userId
                ])->toArray()
            );
        });
    }
}
