<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CropType;
use App\Models\Variety;
use App\Models\Location;
use App\Models\BreederProfile;
use App\Models\Institute;

class ReconstructionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a few custom locations
        $locations = Location::factory(5)->create();

        // 2. Create some core crop types (JSON Schema)
        $rice = CropType::create([
            'name' => 'Rice',
            'scientific_name' => 'Oryza sativa',
            'attributes_schema' => [
                'grain_length' => 'string',
                'amylose_content' => 'number',
                'maturity_days' => 'number'
            ]
        ]);

        $abaca = CropType::create([
            'name' => 'Abaca',
            'scientific_name' => 'Musa textilis',
            'attributes_schema' => [
                'fiber_grade' => 'string',
                'tensile_strength' => 'number'
            ]
        ]);

        // 3. Ensure we have an Institute (fallback to factory if none exists)
        $institute = Institute::first() ?? Institute::factory()->create();

        // 4. Create Varieties mapping dynamically to the JSON schema
        $riceVarieties = Variety::factory(10)->create([
            'crop_type_id' => $rice->id,
            'institute_id' => $institute->id,
            'attributes' => [
                'grain_length' => 'Long',
                'amylose_content' => rand(15, 25),
                'maturity_days' => rand(100, 120)
            ]
        ]);

        // Tag varieties to locations polymorphically
        foreach ($riceVarieties as $variety) {
            $variety->locations()->attach($locations->random());
        }

        // 5. Create some Breeder Profiles tied to the Institute
        $breeders = BreederProfile::factory(5)->create([
            'institute_id' => $institute->id
        ]);
        
        foreach ($breeders as $breeder) {
            $breeder->locations()->attach($locations->random());
        }
    }
}
