<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('system_variables.permissions');

        foreach ($permissions as $key => $permission) {
            foreach ($permission['roles'] as $role) {
                $id = Role::where('value', $role)->first()->id;
                Permission::factory()->create(
                    [
                        'role_id' => $id,
                        'label' => strtoupper($key),
                        'value' => $key,
                    ]
                );
            }
        }
    }
}
