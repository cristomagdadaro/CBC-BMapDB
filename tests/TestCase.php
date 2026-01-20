<?php

namespace Tests;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use App\Enums\Applications as ApplicationsEnum;
use App\Models\Application;
use App\Models\Institute;
use App\Models\Location\City;
use App\Models\Location\Province;
use App\Models\Location\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Modules\PbMap\Enums\Permissions as PbMapPermissions;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected static bool $databaseMigrated = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configureTestDatabase();
        if (!static::$databaseMigrated) {
            $this->artisan('migrate:fresh');
            static::$databaseMigrated = true;
        }
        if (!env('TEST_SKIP_RESET_APP', false)) {
            $this->artisan('db:reset-app');
        }
        $this->ensureBaseLocationData();
        $this->ensureBaseApplicationData();
        $this->userSetup();
    }

    protected function configureTestDatabase(): void
    {
        config([
            'app.key' => env('APP_KEY') ?: 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=',
            'database.default' => env('DB_CONNECTION', 'mysql'),
            'database.connections.mysql.host' => env('DB_HOST', '127.0.0.1'),
            'database.connections.mysql.port' => env('DB_PORT', '3306'),
            'database.connections.mysql.database' => env('DB_DATABASE', 'cbc_db_test'),
            'database.connections.mysql.username' => env('DB_USERNAME', 'root'),
            'database.connections.mysql.password' => env('DB_PASSWORD', ''),
        ]);
    }

    protected function ensureBaseLocationData(): void
    {
        $countryId = DB::table('loc_countries')->value('id');
        if (!$countryId) {
            $countryId = DB::table('loc_countries')->insertGetId([
                'country' => 'Philippines',
                'iso_code' => 'PH',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $region = Region::firstOrCreate([
            'regDesc' => 'NCR',
        ], [
            'regDescLong' => 'National Capital Region',
            'country_id' => $countryId,
        ]);

        $province = Province::firstOrCreate([
            'provDesc' => 'Metro Manila',
        ], [
            'regDesc' => $region->regDesc,
        ]);

        $city = City::firstOrCreate([
            'cityDesc' => 'Manila',
            'provDesc' => $province->provDesc,
            'regDesc' => $region->regDesc,
        ], [
            'latitude' => '14.5995',
            'longitude' => '120.9842',
        ]);

        Institute::firstOrCreate([
            'name' => 'Test Institute',
        ], [
            'inst_type' => 'SUC',
            'geolocation' => $city->id,
            'website' => 'https://example.com',
            'email' => 'test-institute@example.com',
            'phone' => '09123456789',
        ]);
    }

    protected function ensureBaseApplicationData(): void
    {
        Application::firstOrCreate([
            'name' => ApplicationsEnum::BREEDERS_MAP->value,
        ], [
            'description' => ApplicationsEnum::BREEDERS_MAP_DESC->value,
            'url' => ApplicationsEnum::BREEDERS_MAP_ROUTE->value,
            'icon' => ApplicationsEnum::BREEDERS_MAP_LOGO->value,
            'status' => 'true',
        ]);
    }

    protected function userSetup(): void
    {
        foreach (RoleEnum::cases() as $roleEnum) {
            Role::firstOrCreate([
                'name' => $roleEnum->value,
                'guard_name' => 'web',
            ]);
        }

        $role = Role::firstOrCreate([
            'name' => RoleEnum::ADMIN->value,
            'guard_name' => 'web',
        ]);

        $permissions = collect(PbMapPermissions::cases())
            ->map(fn ($permission) => Permission::firstOrCreate([
                'name' => $permission->value,
                'guard_name' => 'web',
            ]))
            ->all();

        $role->syncPermissions($permissions);

        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->assignRole($role);
    }
}
