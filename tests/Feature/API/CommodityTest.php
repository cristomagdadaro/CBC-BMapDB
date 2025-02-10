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
        $response = $this->get('/api/commodities?page=1&per_page=10&sort=id&order=asc&filter_by_parent_id=2&filter_by_parent_column=breeder_id&with=breeder,location,characteristics,additionalinfo');
        print_r($response->getContent());
        $response->assertStatus(200);
    }
}
