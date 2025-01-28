<?php

namespace Database\Seeders;

use App\Enums\Applications;
use App\Modules\Administrator\Models\Application;
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
            'url' => Applications::BREEDERS_MAP_ROUTE->value,
            'icon' => null,
        ]);

        Application::factory()->create([
            'name' => Applications::BREEDERS_MAP->value,
            'description' => 'This interactive platform provides a comprehensive overview of the Philippines\' biotechnology-driven plant breeding community.',
            'url' => Applications::TWG_DATABASE_ROUTE->value,
            'icon' => null,
        ]);
    }
}
