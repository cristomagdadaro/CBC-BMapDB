<?php

namespace Tests\Unit;

use Tests\TestCase;

class ApplicationCrudTest extends TestCase
{
    /** @test **/
    public function get_all_applications(): void
    {
        $response = $this->getJson('/api/applications');

        $response->assertStatus(200);
        $this->assertEquals(10, $response['meta']['total']);
    }

    /** @test **/
    public function get_application_by_id(): void
    {
        $response = $this->getJson('/api/applications/1');

        $response->assertStatus(200);
        $this->assertDatabaseHas('applications', $response->collect()->toArray());
    }

    /** @test **/
    public function get_application_by_id_that_does_not_exist(): void
    {
        $response = $this->getJson('/api/applications/999');

        $response->assertStatus(404);
    }

    /** @test **/
    public function create_an_application(): void
    {
        $response = $this->postJson('/api/applications', [
            'name' => 'Test',
            'description' => 'test',
            'url' => 'https://test.com',
            'icon' => 'https://test.com/icon.png',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('applications', [
            'name' => 'Test',
            'description' => 'test',
            'url' => 'https://test.com',
            'icon' => 'https://test.com/icon.png',
        ]);
    }

    /** @test **/
    public function create_an_application_with_existing_name(): void
    {
        $app = \App\Models\Application::factory()->create();

        $response = $this->postJson('/api/applications', $app->toArray());

        $response->assertStatus(422);
        $this->assertDatabaseCount('applications', 11);
    }


    /** @test **/
    public function update_an_application(): void
    {
        $app = \App\Models\Application::factory()->create();

        $response = $this->putJson('/api/applications/'.$app->id, [
            'name' => 'Test',
            'description' => 'test',
            'url' => 'https://test.com',
            'icon' => 'https://test.com/icon.png',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('applications', [
            'name' => 'Test',
            'description' => 'test',
            'url' => 'https://test.com',
            'icon' => 'https://test.com/icon.png',
        ]);
    }

    /** @test **/
    public function update_an_application_with_existing_name(): void
    {
        $app = \App\Models\Application::factory()->create();
        $response = $this->putJson('/api/applications/'.$app->id, [
            'name' => $app->name,
            'description' => 'test',
            'url' => $app->url,
            'icon' => 'https://test.com/icon.png',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('applications', 11);
    }

    /** @test **/
    public function delete_an_application(): void
    {
        $response = $this->deleteJson('/api/applications/1');

        $response->assertOk();
        $this->assertSoftDeleted('applications', [
            'id' => 1,
        ]);
    }

    /** @test **/
    public function delete_an_application_that_does_not_exist(): void
    {
        $response = $this->deleteJson('/api/applications/999');

        $response->assertStatus(404);
    }


}
