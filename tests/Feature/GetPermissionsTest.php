<?php

namespace Tests\Feature;

class GetPermissionsTest extends BaseTest
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/permissions');

        $response->assertStatus(200);
        $this->assertCount(8, $response['data']);
    }
}
