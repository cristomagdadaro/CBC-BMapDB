<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    /** @test **/
    public function create_user(): void
    {
        $response = $this->post('/api/administrator', [
            'fname' => 'John',
            'mname' => 'Doe',
            'lname' => 'Smith',
            'suffix' => 'Jr',
            'mobile_no' => '09123456789',
            'email' => 'sample@gmail.com',
            'affiliation' => 'Sample Affiliation',
            'account_for' => 1,
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => 'accepted',
        ]);

        $response->assertStatus(200);
    }
}
