<?php

namespace Tests\Unit;

use App\Models\Role;
use Tests\TestCase;

class RoleCrudTest extends TestCase
{
    /** @test **/
    public function get_all_roles(): void
    {
        $response = $this->getJson('/api/roles');

        $response->assertStatus(200);
        $this->assertDatabaseCount('roles', 3);
    }

    /** @test **/
    public function get_a_specific_role(): void
    {
        $response = $this->getJson('/api/roles/2');

        $response->assertStatus(200);
        $this->assertArrayHasKey('id', $response->json());
        $this->assertEquals(2, $response->json()['id']);
        $this->assertDatabaseHas('roles', [
            'id' => 2,
        ]);
    }

    /** @test **/
    public function get_a_non_existing_role(): void
    {
        $response = $this->getJson('/api/roles/999');

        $response->assertStatus(404);
    }

    /** @test **/
    public function create_a_role(): void
    {
        $response = $this->postJson('/api/roles', [
            'label' => 'Test',
            'value' => 'test',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', [
            'label' => 'Test',
            'value' => 'test',
        ]);
    }

    /** @test **/
    public function create_a_role_with_existing_label(): void
    {
        $response = $this->postJson('/api/roles', [
            'label' => 'Admin',
            'value' => 'test',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('roles', 3);
    }

    /** @test **/
    public function cannot_delete_a_role_because_of_foreign_key(): void
    {
        $response = $this->deleteJson('/api/roles/2');

        $response->assertStatus(500);
        $this->assertDatabaseHas('roles', [
            'id' => 2,
        ]);
    }

    /** @test **/
    public function update_a_role(): void
    {
        $response = $this->putJson('/api/roles/3',[
            'label' => 'Update Test',
            'value' => 'updated test',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', [
            'label' => 'Update Test',
            'value' => 'updated test',
        ]);
    }

    /** @test **/
    public function update_a_role_with_existing_label(): void
    {
        $response = $this->putJson('/api/roles/3',[
            'label' => 'Admin',
            'value' => 'updated test',
        ]);

        $response->assertStatus(422);
    }
}
