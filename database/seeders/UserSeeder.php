<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->withPersonalTeam()->create([
            'fname' => 'Cristo Rey',
            'lname' => 'Magdadaro',
            'email' => 'admin@cbc.gov.ph',
            'email_verified_at' => now(),
            'affiliation' => Institute::where('name', env('COMPANY_NAME'))->first()->id
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

        $focalPerson = User::factory()->withPersonalTeam()->create([
            'fname' => 'Cristo Rey',
            'lname' => 'Magdadaro',
            'email' => 'focalperson@cbc.gov.ph',
            'email_verified_at' => now()
        ]);

        $focalPerson->approve(2);

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

        $admin->assignRole(Role::ADMIN->value);
        $twgAdmin->assignRole(Role::TWG_MANAGER->value);
        $breeder->assignRole(Role::BREEDER->value);
        $focalPerson->assignRole(Role::FOCAL_PERSON->value);
        $researcher->assignRole(Role::RESEARCHER->value);
    }
}
