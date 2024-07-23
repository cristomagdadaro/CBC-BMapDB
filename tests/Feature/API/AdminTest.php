<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    /** @test */
    public function get_unverified_users(): void
    {
        $response = $this->get('/api/administrator?filter=email_verified_at&search=null&is_exact=true&page=1&per_page=10&sort=created_at&order=desc');
        print_r($response);
        $response->assertStatus(200);
    }
}
