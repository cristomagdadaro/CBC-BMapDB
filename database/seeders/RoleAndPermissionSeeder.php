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
        Permission::create(['name' => PermissionEnum::CREATE_USER->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_USER->value]);
        Permission::create(['name' => PermissionEnum::DELETE_USER->value]);
        Permission::create(['name' => PermissionEnum::READ_USER->value]);

        Permission::create(['name' => PermissionEnum::CREATE_APP_ACCOUNT->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_APP_ACCOUNT->value]);
        Permission::create(['name' => PermissionEnum::DELETE_APP_ACCOUNT->value]);
        Permission::create(['name' => PermissionEnum::READ_APP_ACCOUNT->value]);

        Permission::create(['name' => PermissionEnum::CREATE_APP->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_APP->value]);
        Permission::create(['name' => PermissionEnum::DELETE_APP->value]);
        Permission::create(['name' => PermissionEnum::READ_APP->value]);

        Permission::create(['name' => PermissionEnum::CREATE_BREEDER->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_BREEDER->value]);
        Permission::create(['name' => PermissionEnum::DELETE_BREEDER->value]);
        Permission::create(['name' => PermissionEnum::READ_BREEDER->value]);

        Permission::create(['name' => PermissionEnum::CREATE_COMMODITY->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_COMMODITY->value]);
        Permission::create(['name' => PermissionEnum::DELETE_COMMODITY->value]);
        Permission::create(['name' => PermissionEnum::READ_COMMODITY->value]);

        Permission::create(['name' => PermissionEnum::CREATE_TWG_EXPERT->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_TWG_EXPERT->value]);
        Permission::create(['name' => PermissionEnum::DELETE_TWG_EXPERT->value]);
        Permission::create(['name' => PermissionEnum::READ_TWG_EXPERT->value]);

        Permission::create(['name' => PermissionEnum::CREATE_TWG_SERVICE->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_TWG_SERVICE->value]);
        Permission::create(['name' => PermissionEnum::DELETE_TWG_SERVICE->value]);
        Permission::create(['name' => PermissionEnum::READ_TWG_SERVICE->value]);

        Permission::create(['name' => PermissionEnum::CREATE_TWG_PRODUCT->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_TWG_PRODUCT->value]);
        Permission::create(['name' => PermissionEnum::DELETE_TWG_PRODUCT->value]);
        Permission::create(['name' => PermissionEnum::READ_TWG_PRODUCT->value]);

        Permission::create(['name' => PermissionEnum::CREATE_TWG_PROJECT->value]);
        Permission::create(['name' => PermissionEnum::UPDATE_TWG_PROJECT->value]);
        Permission::create(['name' => PermissionEnum::DELETE_TWG_PROJECT->value]);
        Permission::create(['name' => PermissionEnum::READ_TWG_PROJECT->value]);

        $adminRole = Role::create(['name' => RoleEnum::ADMIN->value]);
        $twgAdminRole = Role::create(['name' => RoleEnum::TWG_ADMIN->value]);
        $breeder = Role::create(['name' => RoleEnum::BREEDER->value]);
        $researcherRole = Role::create(['name' => RoleEnum::RESEARCHER->value]);

        $adminRole->givePermissionTo(Permission::all());

        $twgAdminRole->givePermissionTo([
            PermissionEnum::CREATE_TWG_EXPERT->value,
            PermissionEnum::UPDATE_TWG_EXPERT->value,
            PermissionEnum::READ_TWG_EXPERT->value,

            PermissionEnum::CREATE_TWG_SERVICE->value,
            PermissionEnum::UPDATE_TWG_SERVICE->value,
            PermissionEnum::READ_TWG_SERVICE->value,

            PermissionEnum::CREATE_TWG_PRODUCT->value,
            PermissionEnum::UPDATE_TWG_PRODUCT->value,
            PermissionEnum::READ_TWG_PRODUCT->value,

            PermissionEnum::CREATE_TWG_PROJECT->value,
            PermissionEnum::UPDATE_TWG_PROJECT->value,
            PermissionEnum::READ_TWG_PROJECT->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ]);

        $breeder->givePermissionTo([
            PermissionEnum::CREATE_BREEDER->value,
            PermissionEnum::UPDATE_BREEDER->value,
            PermissionEnum::READ_BREEDER->value,

            PermissionEnum::CREATE_COMMODITY->value,
            PermissionEnum::UPDATE_COMMODITY->value,
            PermissionEnum::READ_COMMODITY->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ]);



        $researcherRole->givePermissionTo([
            PermissionEnum::READ_BREEDER->value,
            PermissionEnum::READ_COMMODITY->value,

            PermissionEnum::READ_TWG_EXPERT->value,
            PermissionEnum::READ_TWG_SERVICE->value,
            PermissionEnum::READ_TWG_PRODUCT->value,
            PermissionEnum::READ_TWG_PROJECT->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ]);
    }
}
