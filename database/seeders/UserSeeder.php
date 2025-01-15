<?php

namespace Database\Seeders;

use App\Models\Accounts;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(20)->create()->each(function ($user) {
            $temp = rand(1, 2);
            $app = rand(1, 2);
            $accounts = [];
            $accounts[] = Accounts::factory()->make([
                'user_id' => $user->id,
                'app_id' => $app
            ])->toArray();

            if ($temp == 2) {
                $accounts[] = Accounts::factory()->make([
                    'user_id' => $user->id,
                    'app_id' => $app === 1 ? 2 : 1
                ])->toArray();
            }

            $user->accounts()->createMany($accounts);
        });
    }
}
