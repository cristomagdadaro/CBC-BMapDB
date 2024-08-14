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
            ApplicationSeeder::class,
        ]);

        $admin = User::factory()->create([
            'fname' => 'Cristo Rey',
            'lname' => 'Magdadaro',
            'email' => 'admin@cbc.gov.ph',
            'affiliation' => 'Crop Biotechnology Center',
            'email_verified_at' => now()
        ]);

        $admin->approve(1);
        $admin->approve(2);

        $twgAdmin = User::factory()->create([
            'fname' => 'TWG',
            'lname' => 'Admin',
            'email' => 'twgadmin@cbc.gov.ph',
            'affiliation' => 'Crop Biotechnology Center',
            'email_verified_at' => now()
        ]);

        $twgAdmin->approve(1);

        $breeder = User::factory()->create([
            'fname' => 'Reynaldo',
            'lname' => 'Diocton',
            'email' => 'breeder@cbc.gov.ph',
            'affiliation' => 'Crop Biotechnology Center',
            'email_verified_at' => now()
        ]);

        $breeder->approve(2);

        $researcher = User::factory()->create([
            'fname' => 'Precious Mae',
            'lname' => 'Gabato',
            'email' => 'researcher@cbc.gov.ph',
            'affiliation' => 'Crop Biotechnology Center',
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
        $twgAdmin->assignRole(Role::TWG_ADMIN->value);
        $breeder->assignRole(Role::BREEDER->value);
        $researcher->assignRole(Role::RESEARCHER->value);
    }
}
