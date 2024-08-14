<?php

namespace Database\Seeders;

use App\Models\Institute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutes = config('list_of_identified_biotech_intitutes');

        foreach ($institutes as $institute) {
            Institute::create([
                'name' => $institute['name'],
                'inst_type' => $institute['inst_type'],
                'city' => $institute['city'],
                'province' => $institute['province'],
                'region' => $institute['region'],
                'website' => $institute['website'],
                'email' => $institute['email'],
                'phone' => $institute['phone'],
            ]);
        }
    }
}
