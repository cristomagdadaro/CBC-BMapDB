<?php

namespace Tests\Unit;

use Tests\TestCase;

class ApplicationCrudTest extends TestCase
{
    /** @test **/
    public function get_all_applications(): void
    {
        $response = $this->get('/api/applications');

        $response->assertStatus(200);
        $this->assertEquals(10, $response['meta']['total']);
    }

    /** @test **/
    public function get_application_by_id(): void
    {
        $response = $this->get('/api/applications/1');

        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', $response->collect()->toArray());
    }

    public function get_application_by_id_that_does_not_exist(): void
    {
        $response = $this->get('/api/applications/999');

        $response->assertStatus(404);
    }

    /** @test **/
    public function create_an_application(): void
    {
        $response = $this->post('/api/applications', [
            'name' => 'Test',
            'description' => 'test',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('applications', [
            'name' => 'Test',
            'description' => 'test',
        ]);
    }

    /** @test **/
    public function create_an_application_with_existing_name(): void
    {
        $response = $this->post('/api/applications', [
            'name' => 'Admin',
            'description' => 'test',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('applications', 10);
    }


    /** @test **/
    public function update_an_application(): void
    {
        $response = $this->put('/api/applications/1', [
            'name' => 'Test',
            'description' => 'test',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('applications', [
            'name' => 'Test',
            'description' => 'test',
        ]);
    }

    /** @test **/
    public function update_an_application_with_existing_name(): void
    {
        $response = $this->put('/api/applications/1', [
            'name' => 'Admin',
            'description' => 'test',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('applications', 10);
    }

    /** @test **/
    public function delete_an_application(): void
    {
        $response = $this->delete('/api/applications/1');

        $response->assertStatus(200);
        $this->assertDatabaseMissing('applications', [
            'id' => 1,
        ]);
    }


}
