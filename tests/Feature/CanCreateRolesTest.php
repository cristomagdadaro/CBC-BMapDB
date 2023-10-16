<?php

namespace Tests\Feature;

use Database\Seeders\RolesSeeder;
use Tests\TestCase;

class CanCreateRolesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->artisan('migrate:fresh');
        $this->seed(RolesSeeder::class);

        $this->assertDatabaseCount('roles', count(config('system_variables.access_levels')));
    }
}
