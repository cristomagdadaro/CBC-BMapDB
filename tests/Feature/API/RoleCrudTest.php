<?php

namespace Tests\Unit;

use Tests\TestCase;

class RoleCrudTest extends TestCase
{
    /** @test **/
    public function get_all_roles(): void
    {
        $response = $this->getJson('/api/public/roles');

        $response->assertOk();
        $this->assertEquals(3, $response['meta']['total']);
    }

    /** @test **/
    public function get_a_specific_role(): void
    {
        $response = $this->getJson('/api/roles/2');

        $response->assertOk();
        $this->assertDatabaseHas('roles', $response->collect()->toArray());
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

        $response->assertStatus(201);
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

        $response->assertServerError();
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

        $response->assertOk();
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
