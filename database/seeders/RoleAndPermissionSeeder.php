<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => PermissionEnum::CREATE_BREEDER->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_BREEDER->value]);
        Permission::create(['name' => PermissionEnum::DELETE_BREEDER->value]);

        Permission::create(['name' => PermissionEnum::CREATE_COMMODITY->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_COMMODITY->value]);
        Permission::create(['name' => PermissionEnum::DELETE_COMMODITY->value]);

        Permission::create(['name' => PermissionEnum::READ_BREEDER->value]);
        Permission::create(['name' => PermissionEnum::READ_COMMODITY->value]);

        $adminRole = Role::create(['name' => RoleEnum::ADMIN->value]);
        $breeder = Role::create(['name' => RoleEnum::BREEDER->value]);
        $researcherRole = Role::create(['name' => RoleEnum::RESEARCHER->value]);

        $adminRole->givePermissionTo([
            PermissionEnum::CREATE_BREEDER->value,
            PermissionEnum::UPDATE_BREEDER->value,
            PermissionEnum::DELETE_BREEDER->value,
            PermissionEnum::READ_BREEDER->value,

            PermissionEnum::CREATE_COMMODITY->value,
            PermissionEnum::UPDATE_COMMODITY->value,
            PermissionEnum::DELETE_COMMODITY->value,
            PermissionEnum::READ_COMMODITY->value,
        ]);

        $breeder->givePermissionTo([
            PermissionEnum::CREATE_BREEDER->value,
            PermissionEnum::UPDATE_BREEDER->value,
            PermissionEnum::READ_BREEDER->value,

            PermissionEnum::CREATE_COMMODITY->value,
            PermissionEnum::UPDATE_COMMODITY->value,
            PermissionEnum::READ_BREEDER->value,
        ]);

        $researcherRole->givePermissionTo([
            PermissionEnum::READ_BREEDER->value,
            PermissionEnum::READ_COMMODITY->value,
        ]);
    }
}
