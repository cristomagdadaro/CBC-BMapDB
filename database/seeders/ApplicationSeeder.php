<?php

namespace Database\Seeders;

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
        //Application::factory()->count(10)->create();

        Application::factory()->create([
            'name' => 'TWG Database',
            'description' => 'Technical Working Group Database for the Biotechnology Related Projects and Researches',
            'url' => null,
            'icon' => null,
        ]);

        Application::factory()->create([
            'name' => 'Breeder\'s Map',
            'description' => 'This interactive platform provides a comprehensive overview of the Philippines\' biotechnology-driven plant breeding community.',
            'url' => null,
            'icon' => null,
        ]);
    }
}
