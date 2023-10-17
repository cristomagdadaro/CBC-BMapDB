<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanGetAllApplicationsTest extends TestCase
{
    /** @test **/
    public function it_can_get_all_applications(): void
    {
        $response = $this->get('/api/applications');

        $response->assertStatus(200);
        $this->assertCount(10, $response['data']);
    }

    /** @test **/
    public function it_can_search_on_all_searchable_fields(): void
    {
        $response = $this->get('/api/applications?search=1');

        $response->assertStatus(200);
        $this->assertCount(10, $response['data']);
    }
}
