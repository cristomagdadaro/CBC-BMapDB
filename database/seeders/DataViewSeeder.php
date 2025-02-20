<?php

namespace Database\Seeders;

use App\Models\DataView;
use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Models\Commodity;
use Modules\TwgDb\Models\TWGExpert;
use Modules\TwgDb\Models\TWGProduct;
use Modules\TwgDb\Models\TWGProject;
use Modules\TwgDb\Models\TWGService;

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
