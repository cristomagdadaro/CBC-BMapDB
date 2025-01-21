<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Breeder;
use App\Models\Commodity;
use App\Models\User;
use Illuminate\Database\Seeder;

class BreedersMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Breeder::factory()->count(50)->create()->each(function ($breeder) {
            $userId = User::all()->random()->first()->id;
            $breeder->update(['user_id' => $userId]);

            $breeder->commodities()->createMany(
                Commodity::factory()->count(rand(1, 15))->make([
                    'user_id' => $userId
                ])->toArray()
            );


            $user = User::factory()->create([
                'fname' => $breeder->fname,
                'mname' => $breeder->mname,
                'lname' => $breeder->lname,
                'suffix' => $breeder->suffix,
                'affiliation' => $breeder->affiliation,
                'mobile_no' => $breeder->mobile_no,
                'email' => $breeder->email,
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);

            $user->approve(2); //Breeders Map
            $user->assignRole(Role::BREEDER->value);
        });
    }
}
