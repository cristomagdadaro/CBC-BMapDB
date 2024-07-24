<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        $admin = User::factory()->unapproved()->create([
            'fname' => 'Cristo Rey',
            'lname' => 'Magdadaro',
            'email' => 'admin@cbc.gov.ph',
            'affiliation' => 'Crop Biotechnology Center',
            'email_verified_at' => now()
        ]);



        $breeder = User::factory()->unapproved()->create([
            'fname' => 'Reynaldo',
            'lname' => 'Diocton',
            'email' => 'breeder@cbc.gov.ph',
            'affiliation' => 'Crop Biotechnology Center',
            'email_verified_at' => now()
        ]);

        $researcher = User::factory()->unapproved()->create([
            'fname' => 'Precious Mae',
            'lname' => 'Gabato',
            'email' => 'researcher@cbc.gov.ph',
            'affiliation' => 'Crop Biotechnology Center',
            'email_verified_at' => now()
        ]);

        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            BreederSeeder::class,
            CommoditySeeder::class,
            TWGDatabaseSeeder::class
        ]);

        foreach ($admin->accounts as $account) {
            $account->approved(now());
        }

        foreach ($breeder->accounts as $account) {
            $account->approved(now());
        }

        foreach ($researcher->accounts as $account) {
            $account->approved(now());
        }

        $admin->assignRole('Administrator');
        $breeder->assignRole('Breeder');
        $researcher->assignRole('Researcher');
    }
}
