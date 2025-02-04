<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Role;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CityProvinceRegionSeeder::class,
            ApplicationSeeder::class,
            InstituteSeeder::class,
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            BreedersMapSeeder::class,
            TWGDatabaseSeeder::class,
            DataViewSeeder::class,
        ]);

        $users = User::all();

        foreach ($users as $user) {
            //check if user is already assigned a role
            if ($user->hasRole(Role::ADMIN->value) || $user->hasRole(Role::TWG_ADMIN->value)|| $user->hasRole(Role::FOCAL_PERSON->value) || $user->hasRole(Role::BREEDER->value) || $user->hasRole(Role::RESEARCHER->value)) {
                continue;
            }
            $user->assignRole(rand(2, 5));
            switch ($user->role) {
                case Role::TWG_ADMIN->value:
                    $user->approve([1]);
                    break;
                case Role::FOCAL_PERSON->value:
                    $user->approve([1, 2]);
                    break;
                case Role::BREEDER->value:
                    $user->approve([2]);
                    break;
                case Role::RESEARCHER->value:
                    $user->approve([1, 2]);
                    break;
            }
        }
    }
}
