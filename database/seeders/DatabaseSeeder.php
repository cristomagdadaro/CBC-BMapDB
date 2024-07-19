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
        User::factory()->create([
            'fname' => 'Test',
            'lname' => 'User',
            'email' => 'sample@cbc.philrice.gov.ph',
            'affiliation' => 'Crop Biotechnology Center'
        ]);

        $this->call([
            UserSeeder::class,
            RolesSeeder::class,
            PermissionSeeder::class,
            ApplicationSeeder::class,
            AccountsSeeder::class,
            BreederSeeder::class,
            CommoditySeeder::class,
            TWGDatabaseSeeder::class
        ]);
    }
}
