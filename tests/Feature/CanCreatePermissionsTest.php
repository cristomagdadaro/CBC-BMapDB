<?php

namespace Tests\Feature;

use App\Models\User;
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

    /**
     * @test
     */

    public function can_create_permission(): void
    {
        $this->assertDatabaseCount('permissions', 8);
        $this->postJson(route('permission.store'), [
            'role_id' => 1,
            'label' => 'test',
            'value' => 'web'
        ])->assertStatus(201);
        $this->assertDatabaseCount('permissions', 9);
    }

    /**
     * @test
     */
    public function can_update_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->postJson(route('api.permissions.store'), [
            'role_id' => 1,
            'label' => 'test',
            'value' => 'web'
        ])->assertStatus(201);
        $response = $this->delete(route('api.permissions.destroy', ['id' => 9]));
        $response->assertStatus(200);
        $this->assertDatabaseCount('permissions', 8);
    }
}
