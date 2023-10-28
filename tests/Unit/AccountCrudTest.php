<?php

namespace Tests\Unit;

use Tests\TestCase;

class AccountCrudTest extends TestCase
{
    /** @test **/
    public function get_all_applications(): void
    {
        $response = $this->get('/api/accounts');

        $response->assertStatus(200);
        $this->assertEquals(147, $response['meta']['total']);
    }
}
