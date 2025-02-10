<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $application = config('system_variables.applications');

        foreach ($application as $app)
            Application::factory()->create([
                'name' => $app['name'],
                'description' => $app['description'],
                'url' => $app['route'],
                'icon' => $app['logo'],
            ]);

    }
}
