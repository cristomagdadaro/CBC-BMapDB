<?php

namespace Tests\Feature;

use App\Models\AccountFor;

class CanGetAllAccountForTest extends BaseTest
{
    /**
     * @test
     */
    public function it_can_get_all_account_for(): void
    {
        AccountFor::factory()->count(10)->create();

        $response = $this->get('/api/account-for');

        $response->assertStatus(200);
        $this->assertCount(10, $response['data']);
    }
}
