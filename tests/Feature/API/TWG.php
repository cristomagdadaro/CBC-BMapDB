<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TWG extends TestCase
{
    /** @test **/
    public function get_summary(): void
    {
        $response = $this->get('/api/twg/summary');
        print_r($response->getContent());
        $response->assertStatus(200);
    }

    /** @test **/
    public function create_an_expert(): void
    {
        $user = User::factory()->create();
        $response = $this->post('/api/twg/experts', [
            'user_id' => $user->id,
            'name' => 'Test Expert',
            'position' => 'Test Position',
            'educ_level' => 'Doctoral',
            'expertise' => 'Test Expertise',
            'research_interest' => 'Test Research',
            'mobile' => '1234567890',
            'email' => 'sample@email,com',
        ]);

        $response->assertStatus(200);
    }
}
