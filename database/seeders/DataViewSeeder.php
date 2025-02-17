<?php

namespace Database\Seeders;

use App\Models\Breeder;
use App\Models\Commodity;
use App\Models\DataView;
use App\Models\TWGExpert;
use App\Models\TWGProduct;
use App\Models\TWGProject;
use App\Models\TWGService;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allUser = User::all();
        $visibilityGuards = config('system_variables.dataview_guards');
        $faker = \Faker\Factory::create(); // Correct Faker import
        $modelClass = [
            Breeder::class,
            Commodity::class,
            TWGExpert::class,
            TWGProduct::class,
            TWGProject::class,
            TWGService::class
        ];

        foreach ($allUser as $user) {
            foreach ($modelClass as $model) {
                foreach ($visibilityGuards as $guard) {
                    // Check if entry exists, otherwise create it
                    DataView::firstOrCreate(
                        [
                            'user_account_id' => $user->id,
                            'model' => (new $model)->getTable(),
                            'visibility_guard' => $guard,
                        ],
                        [
                            'uuid' => $faker->uuid(), // Generate UUID
                            'columns' => (new $model)->getSearchable(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }


}
