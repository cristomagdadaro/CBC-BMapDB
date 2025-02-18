<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DataViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allUser = User::all();

        foreach ($allUser as $user) {
            $user->generateDataView();
        }
    }


}
