<?php

namespace Tests\Feature;

use Database\Seeders\PermissionSeeder;

class CanCreatePermissionsTest extends BaseTest
{
    /**
     * @test
     */
    public function can_get_all_permission(): void
    {
        $this->assertDatabaseCount('permissions', 8);
    }
}
