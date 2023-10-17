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
}
