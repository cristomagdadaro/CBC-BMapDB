<?php

namespace Tests\Feature;

use App\Models\AccountFor;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class CanGetAllAccountForTest extends BaseTest
{
    /**
     * @test
     */
    public function it_can_get_all_account_for_a_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        AccountFor::factory()->count(10)->create();

        $response = $this->get("/api/account-for/{$user->id}/accounts");

        $response->assertStatus(200);
        $this->assertCount(10, $response->json()['data']);
    }

    /** @test **/
    public function it_can_sort_account_for_by_id(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        AccountFor::factory()->count(10)->create();

        $response = $this->get("/api/account-for/{$user->id}/accounts?sort=id&order=desc");

        $response->assertStatus(200);
        $this->assertCount(10, $response->json()['data']);
        $this->assertEquals(10, $response->json()['data'][0]['id']);
    }

    /** @test **/
    public function it_can_search_a_string_across_all_account_for():void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        AccountFor::factory()->create([
            'user_id' => $user->id,
            'account_id' => 1010101010,
        ]);

        AccountFor::factory()->count(10)->create([
            'user_id' => $user->id,
            'account_id' => 1,
        ]);

        $response = $this->get("{$user->id}/accounts?search=1010101010");

        $response->assertStatus(200);
        $this->assertCount(1, $response->json()['data']);
    }
}
