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
        Permission::firstOrCreate(['name' => PermissionEnum::CREATE_USER->value]);
        Permission::firstOrCreate(['name' => PermissionEnum::UPDATE_USER->value]);
        Permission::firstOrCreate(['name' => PermissionEnum::DELETE_USER->value]);
        Permission::firstOrCreate(['name' => PermissionEnum::READ_USER->value]);

        Permission::firstOrCreate(['name' => PermissionEnum::CREATE_APP_ACCOUNT->value]);
        Permission::firstOrCreate(['name' => PermissionEnum::UPDATE_APP_ACCOUNT->value]);
        Permission::firstOrCreate(['name' => PermissionEnum::DELETE_APP_ACCOUNT->value]);
        Permission::firstOrCreate(['name' => PermissionEnum::READ_APP_ACCOUNT->value]);

        Permission::firstOrCreate(['name' => PermissionEnum::CREATE_APP->value]);
        Permission::firstOrCreate(['name' => PermissionEnum::UPDATE_APP->value]);
        Permission::firstOrCreate(['name' => PermissionEnum::DELETE_APP->value]);
        Permission::firstOrCreate(['name' => PermissionEnum::READ_APP->value]);

        Permission::firstOrCreate(['name' => PbMapPermissions::CREATE_BREEDER->value]);
        Permission::firstOrCreate(['name' => PbMapPermissions::UPDATE_BREEDER->value]);
        Permission::firstOrCreate(['name' => PbMapPermissions::DELETE_BREEDER->value]);
        Permission::firstOrCreate(['name' => PbMapPermissions::READ_BREEDER->value]);

        Permission::firstOrCreate(['name' => PbMapPermissions::CREATE_COMMODITY->value]);
        Permission::firstOrCreate(['name' => PbMapPermissions::UPDATE_COMMODITY->value]);
        Permission::firstOrCreate(['name' => PbMapPermissions::DELETE_COMMODITY->value]);
        Permission::firstOrCreate(['name' => PbMapPermissions::READ_COMMODITY->value]);

        Permission::firstOrCreate(['name' => PbMapPermissions::CREATE_COMMODITY_REQUEST->value]);
        Permission::firstOrCreate(['name' => PbMapPermissions::UPDATE_COMMODITY_REQUEST->value]);
        Permission::firstOrCreate(['name' => PbMapPermissions::DELETE_COMMODITY_REQUEST->value]);
        Permission::firstOrCreate(['name' => PbMapPermissions::READ_COMMODITY_REQUEST->value]);

        Permission::firstOrCreate(['name' => TwgDbPermissions::CREATE_TWG_EXPERT->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::UPDATE_TWG_EXPERT->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::DELETE_TWG_EXPERT->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::READ_TWG_EXPERT->value]);

        Permission::firstOrCreate(['name' => TwgDbPermissions::CREATE_TWG_SERVICE->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::UPDATE_TWG_SERVICE->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::DELETE_TWG_SERVICE->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::READ_TWG_SERVICE->value]);

        Permission::firstOrCreate(['name' => TwgDbPermissions::CREATE_TWG_PRODUCT->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::UPDATE_TWG_PRODUCT->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::DELETE_TWG_PRODUCT->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::READ_TWG_PRODUCT->value]);

        Permission::firstOrCreate(['name' => TwgDbPermissions::CREATE_TWG_PROJECT->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::UPDATE_TWG_PROJECT->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::DELETE_TWG_PROJECT->value]);
        Permission::firstOrCreate(['name' => TwgDbPermissions::READ_TWG_PROJECT->value]);

        $adminRole = Role::firstOrCreate(['name' => RoleEnum::ADMIN->value]);
        $twgAdminRole = Role::firstOrCreate(['name' => RoleEnum::TWG_MANAGER->value]);
        $breederAdmin = Role::firstOrCreate(['name' => RoleEnum::FOCAL_PERSON->value]);
        $breeder = Role::firstOrCreate(['name' => RoleEnum::BREEDER->value]);
        $researcherRole = Role::firstOrCreate(['name' => RoleEnum::RESEARCHER->value]);

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

            PbMapPermissions::CREATE_COMMODITY_REQUEST->value,
            PbMapPermissions::UPDATE_COMMODITY_REQUEST->value,
            PbMapPermissions::READ_COMMODITY_REQUEST->value,
            PbMapPermissions::DELETE_COMMODITY_REQUEST->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ]);

        $breeder->givePermissionTo([
            PbMapPermissions::CREATE_COMMODITY->value,
            PbMapPermissions::UPDATE_COMMODITY->value,
            PbMapPermissions::READ_COMMODITY->value,

            PbMapPermissions::CREATE_COMMODITY_REQUEST->value,
            PbMapPermissions::UPDATE_COMMODITY_REQUEST->value,
            PbMapPermissions::READ_COMMODITY_REQUEST->value,

            PbMapPermissions::READ_BREEDER->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ]);

        // permissions for researchers will be given by the administrator upon request
        $researcherRole->givePermissionTo([
            PermissionEnum::CREATE_APP_ACCOUNT->value
        ]);
    }
}
