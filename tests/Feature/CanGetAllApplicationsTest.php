<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

class CanGetAllApplicationsTest extends BaseTest
{

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test **/
    public function it_can_get_all_applications(): void
    {
        $response = $this->get('/api/applications');
        print_r($response['data']);
        $response->assertStatus(200);
        $this->assertCount(20, $response['data']);
    }

    /** @test **/
    public function it_can_search_on_all_searchable_fields(): void
    {
        $response = $this->get('/api/applications?search=1');

        $response->assertStatus(200);
        $this->assertCount(20, $response['data']);
    }

    /** @test **/
    public function it_can_get_the_applications_owned_by_the_user(): void
    {
        $user = User::factory()->withAccountFor()->create();
        Sanctum::actingAs($user);


        $response = $this->get('/api/applications?owner_id=' . $user->id);

        $response->assertStatus(200);
        $this->assertCount(20, $response['data']);
    }
}
