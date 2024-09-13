<?php

namespace Database\Seeders;

use App\Enums\Applications;
use App\Models\Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Application::factory()->create([
            'name' => Applications::TWG_DATABASE->value,
            'description' => 'Technical Working Group Database for the Biotechnology Related Projects and Researches',
            'url' => 'projects.twg.index',
            'icon' => null,
        ]);

        Application::factory()->create([
            'name' => Applications::BREEDERS_MAP->value,
            'description' => 'This interactive platform provides a comprehensive overview of the Philippines\' biotechnology-driven plant breeding community.',
            'url' => 'projects.breedersmap.index',
            'icon' => null,
        ]);
    }
}
