<?php

namespace Database\Factories;

use App\Models\Breeder;
use App\Models\Commodity;
use App\Models\TWGExpert;
use App\Models\TWGProduct;
use App\Models\TWGProject;
use App\Models\TWGService;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

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

        do {
            $user_account_id = User::query()->inRandomOrder()->value('id');

            $success = DB::table('data_views')->where('user_account_id', $user_account_id)->where('model', $table_name)->count();
            print_r($success);
        } while ($success);

        return [
            'uuid' => $this->faker->uuid(),
            'user_account_id' => $user_account_id,
            'model' => $table_name,
            'columns' => implode(',', (new $modelClass)->getSearchable() ?? []),
            'visibility_guard' => $this->faker->randomElement(config('system_variables.dataview_guards'))
        ];
    }
}
