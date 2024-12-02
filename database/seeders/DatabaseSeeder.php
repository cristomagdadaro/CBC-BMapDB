<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Role;
use App\Models\Accounts;
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
        ]);

        $admin = User::factory()->withPersonalTeam()->create([
            'fname' => 'Cristo Rey',
            'lname' => 'Magdadaro',
            'email' => 'admin@cbc.gov.ph',
            'email_verified_at' => now()
        ]);

        $admin->approve(1);
        $admin->approve(2);

        $twgAdmin = User::factory()->withPersonalTeam()->create([
            'fname' => 'TWG',
            'lname' => 'Admin',
            'email' => 'twgadmin@cbc.gov.ph',
            'email_verified_at' => now()
        ]);

        $twgAdmin->approve(1);

        $breeder = User::factory()->withPersonalTeam()->create([
            'fname' => 'Reynaldo',
            'lname' => 'Diocton',
            'email' => 'breeder@cbc.gov.ph',
            'email_verified_at' => now()
        ]);

        $breeder->approve(2);

        $researcher = User::factory()->withPersonalTeam()->create([
            'fname' => 'Precious Mae',
            'lname' => 'Gabato',
            'email' => 'researcher@cbc.gov.ph',
            'email_verified_at' => now()
        ]);

        $researcher->approve(1);
        $researcher->approve(2);

        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            BreederSeeder::class,
            CommoditySeeder::class,
            TWGDatabaseSeeder::class
        ]);


        $admin->assignRole(Role::ADMIN->value);
        $twgAdmin->assignRole(Role::EXPERT->value);
        $breeder->assignRole(Role::BREEDER->value);
        $researcher->assignRole(Role::RESEARCHER->value);

        $users = User::all();
        foreach ($users as $user) {
            //check if user is already assigned a role
            if ($user->hasRole(Role::ADMIN->value) || $user->hasRole(Role::EXPERT->value)|| $user->hasRole(Role::FOCAL_PERSON->value) || $user->hasRole(Role::BREEDER->value) || $user->hasRole(Role::RESEARCHER->value)) {
                continue;
            }
            $user->assignRole(rand(2, 5));
            switch ($user->role) {
                case Role::EXPERT->value:
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
