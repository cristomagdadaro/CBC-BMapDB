<?php

namespace Tests\Feature;

class GetRolesTest extends BaseTest
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/roles');

        $response->assertStatus(200);
        $this->assertCount(3, $response['data']);
    }
}
