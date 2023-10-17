<?php

namespace Tests\Feature;

class CanCreateRolesTest extends BaseTest
{
    /**
     * @test
     */
    public function can_get_all_roles(): void
    {
        $this->assertDatabaseCount('roles', count(config('system_variables.access_levels')));
    }
}
