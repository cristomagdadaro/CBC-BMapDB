<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('system_variables.permissions');
        foreach ($permissions as $permission) {
            Permission::factory()->create(
                [
                    'label' => strtoupper($permission->value),
                    'value' => $permission->value,
                ]
            );
        }
    }
}
