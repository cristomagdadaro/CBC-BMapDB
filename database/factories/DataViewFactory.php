<?php

namespace Database\Factories;

use App\Models\DataView;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Models\Commodity;
use Modules\TwgDb\Models\TWGExpert;
use Modules\TwgDb\Models\TWGProduct;
use Modules\TwgDb\Models\TWGProject;
use Modules\TwgDb\Models\TWGService;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataView>
 */
class DataViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $modelClass = $this->faker->randomElement([
            Breeder::class,
            Commodity::class,
            TWGExpert::class,
            TWGProduct::class,
            TWGProject::class,
            TWGService::class
        ]);

        $table_name = (new $modelClass)->getTable();
        $visibility_guard = $this->faker->randomElement(config('system_variables.dataview_guards'));

        // Find a unique user_account_id
        $user_account_id = User::query()
            ->inRandomOrder()
            ->pluck('id')
            ->first();

        // Ensure uniqueness before inserting using UUID as primary key
        return DataView::firstOrCreate(
            [
                'user_account_id' => $user_account_id,
                'model' => $table_name,
                'visibility_guard' => $visibility_guard
            ],
            [
                'uuid' => $this->faker->uuid(), // Explicitly define UUID
                'columns' => implode(',', (new $modelClass)->getSearchable() ?? []),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        )->toArray();
    }
}
