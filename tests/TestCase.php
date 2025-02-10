<?php

namespace Tests;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        //$this->artisan('migrate:fresh');
        $this->artisan('db:reset-app');
        $this->userSetup();
    }

    protected function userSetup(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $user->assignRole(RoleEnum::ADMIN->value);
    }
}
