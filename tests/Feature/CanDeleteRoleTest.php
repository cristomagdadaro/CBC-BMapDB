<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanDeleteRoleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->delete('/permissions?id=1');
        print($response->getContent());
        $response->assertStatus(500);
    }
}
