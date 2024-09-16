<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommodityTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/public/commodities/summary?geo_location_value=CAR&is_exact=true&geo_location_filter=region&commodity=Coffee');
        print_r($response->getContent());
        $response->assertStatus(200);
    }
}
