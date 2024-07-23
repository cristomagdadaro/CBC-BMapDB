<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;

class PermissionCrudTest extends TestCase
{
    /** @test **/
    public function get_all_permissions(): void
    {
        $response = $this->getJson('/api/permissions');

        $response->assertStatus(200);
        $response->assertJsonCount(8, 'data');
    }

    /** @test **/
    public function get_permission_by_id(): void
    {
        $response = $this->getJson('/api/permissions/1');

        $response->assertStatus(200);
        $this->assertDatabaseHas('permissions', $response->collect()->toArray());
    }

    /** @test **/
    public function create_permission(): void
    {
        $role = Role::factory()->create();

        $response = $this->postJson('/api/permissions', [
            'role_id' => $role->id,
            'label' => 'Sample Role',
            'value' => 'sample_permission',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('permissions', [
            'role_id' => $role->id,
            'label' => 'Sample Role',
            'value' => 'sample_permission',
        ]);
    }

    /** @test **/
    public function update_permission(): void
    {
        $permission = Permission::factory()->create(
            [
                'role_id' => '1',
                'label' => 'Sample Role',
                'value' => 'sample_permission',
            ]
        );

        $response = $this->putJson('/api/permissions/'.$permission->id, [

            'role_id' => '2',
            'label' => 'Updated Sample Role',
            'value' => 'updated_sample_permission',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('permissions', [
            'role_id' => '2',
            'label' => 'Updated Sample Role',
            'value' => 'updated_sample_permission',
        ]);
    }

    /** @test **/
    public function delete_permission(): void
    {
        $response = $this->deleteJson('/api/permissions/1');

        $response->assertStatus(200);

        $this->assertDatabaseMissing('permissions', [
            'id' => 1,
        ]);
    }

    /** @test **/
    public function delete_permission_that_does_not_exist(): void
    {
        $response = $this->deleteJson('/api/permissions/999');

        $response->assertStatus(404);
    }

    /** @test **/
    public function create_permission_with_invalid_data(): void
    {
        $response = $this->postJson('/api/permissions', [
            'role_id' => 9999,
            'label' => 'Sample Role',
            'value' => 'sample_permission',
        ]);

        $response->assertStatus(422);
    }
}
