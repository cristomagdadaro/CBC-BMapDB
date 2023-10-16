<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanMakeApplicationsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed ApplicationSeeder');

        $response = $this->get('/api/applications');

        $response->assertStatus(200);
        $this->assertCount(10, $response['data']);
    }
}
