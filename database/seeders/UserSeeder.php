<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Modules\Administrator\Models\Accounts;
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
        /*User::factory()->count(20)->create()->each(function ($user) {
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
        });*/

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
        $twgAdmin->assignRole(Role::TWG_ADMIN->value);
        $breeder->assignRole(Role::BREEDER->value);
        $focalPerson->assignRole(Role::FOCAL_PERSON->value);
        $researcher->assignRole(Role::RESEARCHER->value);

        $this->insertTWGUserAccounts();
    }

    private function insertTWGUserAccounts()
    {
        $users = [
            [
                'fname' => 'Dr. Annie',
                'mname' => 'Q.',
                'lname' => 'Bares',
                'email' => 'ilocos@da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'RFO Region I')->first()->id,
                'mobile_no' => '(072) 242-1045',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Dr. Rose Mary',
                'mname' => 'G.',
                'lname' => 'Aquino',
                'email' => 'ored.rfo2@da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'RFO Region II')->first()->id,
                'mobile_no' => '(078) 396-9769',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Dr. Edaurdo',
                'mname' => 'L.',
                'lname' => 'Lapuz',
                'email' => 'ored@rfo3.da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'RFO Region III')->first()->id,
                'mobile_no' => '(045) 961-3075',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Rodel',
                'mname' => 'P.',
                'lname' => 'Tornilla',
                'email' => 'da5ored@yahoo.com',
                'affiliation' => Institute::all()->where('name', 'RFO Region V')->first()->id,
                'mobile_no' => '(054) 477-0381',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Andrew Rodolfo',
                'mname' => 'T.',
                'lname' => 'Orais',
                'email' => 'da8ored1@gmail.com',
                'affiliation' => Institute::all()->where('name', 'RFO Region VIII')->first()->id,
                'mobile_no' => '(053) 325-7242',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Dr. John',
                'mname' => 'C.',
                'lname' => 'De Leon',
                'email' => 'prri.mail@philrice.gov.ph',
                'affiliation' => Institute::all()->where('name', 'Philippine Rice Research Institute')->first()->id,
                'mobile_no' => null,
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Precila',
                'mname' => 'C.',
                'lname' => 'Belmonte',
                'email' => 'philrootcrops@vsu.edu.ph',
                'affiliation' => Institute::all()->where('name', 'Philippine Rice Research Institute')->first()->id,
                'mobile_no' => '63 (053) 565 0600',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Junel',
                'mname' => 'B.',
                'lname' => 'Soriano',
                'email' => 'jsoriano@bar.gov.ph',
                'affiliation' => Institute::all()->where('name', 'Bureau of Agricultural Research')->first()->id,
                'mobile_no' => '(02) 8461-2900',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Gerald Glen',
                'mname' => 'F.',
                'lname' => 'Panganiban',
                'email' => 'bpi.do@buplant.da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'Bureau of Plant Industry')->first()->id,
                'mobile_no' => '(02) 8332-7567',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Evelyn',
                'mname' => 'B.',
                'lname' => 'Pagasan',
                'email' => 'oed@philfida.da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'Philippine Fiber Industry Development Authority')->first()->id,
                'mobile_no' => '(02) 8928-8741',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Belinda',
                'mname' => 'S.',
                'lname' => 'Sanchez',
                'email' => 'NA',
                'affiliation' => Institute::all()->where('name', 'National Tobacco Administration')->first()->id,
                'mobile_no' => '(02) 8374-3987',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Bernie',
                'mname' => 'F.',
                'lname' => 'Cruz',
                'email' => 'pca.ofad@gmail.com',
                'affiliation' => Institute::all()->where('name', 'Philippine Coconut Authority')->first()->id,
                'mobile_no' => '(02) 8927-8706',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Pablo Luis',
                'mname' => 'S.',
                'lname' => 'Azcona',
                'email' => 'srahead@sra.gov.ph',
                'affiliation' => Institute::all()->where('name', 'Sugar Regulatory Administration')->first()->id,
                'mobile_no' => '(02) 8929-3633',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Fidel',
                'mname' => 'L.',
                'lname' => 'Libao',
                'email' => 'ored@calabarzon.da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'RFO Region IV-A')->first()->id,
                'mobile_no' => '(02) 8928-8741',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Christopher',
                'mname' => 'R.',
                'lname' => 'Bañas',
                'email' => 'mimaropa@mail.da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'RFO Region IV-B')->first()->id,
                'mobile_no' => '(02) 8927-4350',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Dennis',
                'mname' => 'R.',
                'lname' => 'Arpia',
                'email' => 'westernvisayas@mail.da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'RFO Region VI')->first()->id,
                'mobile_no' => '(033) 337-1262',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Angel',
                'mname' => 'C.',
                'lname' => 'Enriquez',
                'email' => 'redsoffice7@gmail.com',
                'affiliation' => Institute::all()->where('name', 'RFO Region VII')->first()->id,
                'mobile_no' => '(033) 337-1262',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Jennilyn',
                'mname' => 'M.',
                'lname' => 'Dawayan',
                'email' => 'da_carfu@yahoo.com',
                'affiliation' => Institute::all()->where('name', 'RFO CAR')->first()->id,
                'mobile_no' => '(074) 445-4973',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Marcos',
                'mname' => 'C.',
                'lname' => 'Aves',
                'suffix' => 'Sr.',
                'email' => 'rfo9@mail.da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'RFO Region IX')->first()->id,
                'mobile_no' => '(062) 214-4677',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Carlene',
                'mname' => 'C.',
                'lname' => 'Collado',
                'email' => 'da10ored@gmail.com',
                'affiliation' => Institute::all()->where('name', 'RFO Region X')->first()->id,
                'mobile_no' => '(088) 856-6871',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Abel James',
                'mname' => 'I.',
                'lname' => 'MonteaGudo',
                'email' => 'darfoxi.red@gmail.com',
                'affiliation' => Institute::all()->where('name', 'RFO Region XI')->first()->id,
                'mobile_no' => '(082) 221-9697',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'John',
                'mname' => 'B.',
                'lname' => 'Pascual',
                'email' => 'ored@rfo12.da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'RFO Region XII')->first()->id,
                'mobile_no' => '(083) 228-3413',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Arlan',
                'mname' => 'M.',
                'lname' => 'Mangelen',
                'email' => 'caraga@mail.da.gov.ph',
                'affiliation' => Institute::all()->where('name', 'RFO Region XIII')->first()->id,
                'mobile_no' => '(085) 341-4546',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Mohammad',
                'mname' => 'S.',
                'lname' => 'Yacob',
                'email' => 'mafar@bangsamoro.gov.ph',
                'affiliation' => Institute::all()->where('name', 'MAFAR BARRM')->first()->id,
                'mobile_no' => '(064) 421-1248',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'fname' => 'Dr. Cheryl',
                'mname' => 'L.',
                'lname' => 'Eusala',
                'email' => 'cheryleusala@gmail.com',
                'affiliation' => Institute::all()->where('name', 'Philippine Rubber Research Institute')->first()->id,
                'mobile_no' => '(062) 055-1622',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            $user = User::create($user);
            $user->assignRole(Role::TWG_ADMIN->value);
            $user->accounts()->create([
                'user_id' => $user->id,
                'app_id' => 1,
                'approved_at' => now(),
            ]);
        }
    }
}
