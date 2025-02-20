<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Enums\Permission as PermissionEnum;
use Modules\PbMap\Enums\Permissions as PbMapPermissions;
use Modules\TwgDb\Enums\Permissions as TwgDbPermissions;
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

        Permission::create(['name' => PbMapPermissions::CREATE_BREEDER->value]);
        Permission::create(['name' => PbMapPermissions::UPDATE_BREEDER->value]);
        Permission::create(['name' => PbMapPermissions::DELETE_BREEDER->value]);
        Permission::create(['name' => PbMapPermissions::READ_BREEDER->value]);

        Permission::create(['name' => PbMapPermissions::CREATE_COMMODITY->value]);
        Permission::create(['name' => PbMapPermissions::UPDATE_COMMODITY->value]);
        Permission::create(['name' => PbMapPermissions::DELETE_COMMODITY->value]);
        Permission::create(['name' => PbMapPermissions::READ_COMMODITY->value]);

        Permission::create(['name' => TwgDbPermissions::CREATE_TWG_EXPERT->value]);
        Permission::create(['name' => TwgDbPermissions::UPDATE_TWG_EXPERT->value]);
        Permission::create(['name' => TwgDbPermissions::DELETE_TWG_EXPERT->value]);
        Permission::create(['name' => TwgDbPermissions::READ_TWG_EXPERT->value]);

        Permission::create(['name' => TwgDbPermissions::CREATE_TWG_SERVICE->value]);
        Permission::create(['name' => TwgDbPermissions::UPDATE_TWG_SERVICE->value]);
        Permission::create(['name' => TwgDbPermissions::DELETE_TWG_SERVICE->value]);
        Permission::create(['name' => TwgDbPermissions::READ_TWG_SERVICE->value]);

        Permission::create(['name' => TwgDbPermissions::CREATE_TWG_PRODUCT->value]);
        Permission::create(['name' => TwgDbPermissions::UPDATE_TWG_PRODUCT->value]);
        Permission::create(['name' => TwgDbPermissions::DELETE_TWG_PRODUCT->value]);
        Permission::create(['name' => TwgDbPermissions::READ_TWG_PRODUCT->value]);

        Permission::create(['name' => TwgDbPermissions::CREATE_TWG_PROJECT->value]);
        Permission::create(['name' => TwgDbPermissions::UPDATE_TWG_PROJECT->value]);
        Permission::create(['name' => TwgDbPermissions::DELETE_TWG_PROJECT->value]);
        Permission::create(['name' => TwgDbPermissions::READ_TWG_PROJECT->value]);

        $adminRole = Role::create(['name' => RoleEnum::ADMIN->value]);
        $twgAdminRole = Role::create(['name' => RoleEnum::TWG_ADMIN->value]);
        $breederAdmin = Role::create(['name' => RoleEnum::FOCAL_PERSON->value]);
        $breeder = Role::create(['name' => RoleEnum::BREEDER->value]);
        $researcherRole = Role::create(['name' => RoleEnum::RESEARCHER->value]);

        $adminRole->givePermissionTo(Permission::all());

        $twgAdminRole->givePermissionTo([
            TwgDbPermissions::CREATE_TWG_EXPERT->value,
            TwgDbPermissions::UPDATE_TWG_EXPERT->value,
            TwgDbPermissions::READ_TWG_EXPERT->value,
            TwgDbPermissions::DELETE_TWG_EXPERT->value,

            TwgDbPermissions::CREATE_TWG_SERVICE->value,
            TwgDbPermissions::UPDATE_TWG_SERVICE->value,
            TwgDbPermissions::READ_TWG_SERVICE->value,
            TwgDbPermissions::DELETE_TWG_SERVICE->value,

            TwgDbPermissions::CREATE_TWG_PRODUCT->value,
            TwgDbPermissions::UPDATE_TWG_PRODUCT->value,
            TwgDbPermissions::READ_TWG_PRODUCT->value,
            TwgDbPermissions::DELETE_TWG_PRODUCT->value,

            TwgDbPermissions::CREATE_TWG_PROJECT->value,
            TwgDbPermissions::UPDATE_TWG_PROJECT->value,
            TwgDbPermissions::READ_TWG_PROJECT->value,
            TwgDbPermissions::DELETE_TWG_PROJECT->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ]);

        $breederAdmin->givePermissionTo([
            PbMapPermissions::CREATE_BREEDER->value,
            PbMapPermissions::UPDATE_BREEDER->value,
            PbMapPermissions::READ_BREEDER->value,
            PbMapPermissions::DELETE_BREEDER->value,

            PbMapPermissions::CREATE_COMMODITY->value,
            PbMapPermissions::UPDATE_COMMODITY->value,
            PbMapPermissions::READ_COMMODITY->value,
            PbMapPermissions::DELETE_COMMODITY->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ]);

        $breeder->givePermissionTo([
            PbMapPermissions::CREATE_COMMODITY->value,
            PbMapPermissions::UPDATE_COMMODITY->value,
            PbMapPermissions::READ_COMMODITY->value,
            PbMapPermissions::READ_BREEDER->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ]);

        // permissions for researchers will be given by the administrator upon request
        $researcherRole->givePermissionTo([
            PermissionEnum::CREATE_APP_ACCOUNT->value
        ]);
    }
}
