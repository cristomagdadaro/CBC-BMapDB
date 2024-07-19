<?php

namespace Tests\Unit;

use Tests\Feature\BaseTest;

class GeoDataTest extends BaseTest
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/geodata');
        print_r($response->getContent());
        $response->assertStatus(200);
    }
}
