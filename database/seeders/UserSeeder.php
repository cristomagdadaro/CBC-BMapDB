<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->approved()
            ->count(20)
            ->create();

        // assign random roles to users
        $users = User::all();
        foreach ($users as $user) {
            $user->assignRole(rand(2, 4));
        }
    }
}
