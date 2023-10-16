<?php

namespace Tests\Feature;

use Database\Seeders\PermissionSeeder;
use Tests\TestCase;

class CanCreatePermissionsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->artisan('migrate:fresh');
        $this->seed(PermissionSeeder::class);

        $this->assertDatabaseCount('permissions', 8);
    }
}
