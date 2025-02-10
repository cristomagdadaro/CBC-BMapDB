<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DataViewTest extends TestCase
{
    /**
     * @test
     */
    public function get_all_data_views(): void
    {
        $response = $this->getJson('/api/data-views');

        $response->assertStatus(200);
    }
}
