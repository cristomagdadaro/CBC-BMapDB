<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = config('system_variables.access_levels');
        foreach ($roles as $role) {
            Role::factory()->create(
                [
                    'label' => strtoupper($role->value),
                    'value' => $role->value,
                ]
            );
        }
    }
}
