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

    /** @test */
    public function delete_a_user(): void
    {
        $this->userSetup();
        $response = $this->deleteJson(route('api.administrator.destroy', 1));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('breeders', [
            'id' => [1],
        ]);
    }

    /** @test */
    public function delete_multiple_users(): void
    {
        $this->userSetup();
        $response = $this->deleteJson(route('api.administrator.destroy.multi'), [
            'ids' => [3, 4, 5],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('breeders', [
            'id' => [3, 4, 5],
        ]);
    }

    /** @test */
    public function update_account(): void
    {
        $this->userSetup();
        $response = $this->putJson(route('api.accounts.update', 1), [
            'user_id' => auth()->id(),
            'app_id' => 1,
            'role' => [-1],
        ]);

        $response->assertStatus(200);
    }
}
