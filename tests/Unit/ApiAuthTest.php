<?php

namespace Tests\Unit;

use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    /** @test **/
    public function can_log_in_api_auth(): void
    {
        $response = $this->post('/api/auth/login',
            [
                'email' => 'sample@cbc.philrice.gov.ph',
                'password' => 'password',
            ]);
        $response->assertStatus(200);
    }

    /** @test **/
    public function can_log_out_api_auth(): void
    {
        $response = $this->post('/api/auth/logout');
        $response->assertStatus(200);
    }

    /** @test **/
    public function can_register_api_auth(): void
    {
        $response = $this->post('/api/auth/register',
            [
                'fname' => 'Sample',
                'lname' => 'Api',
                'mname' => 'Auth',
                'mobile_no' => '09123456789',
                'email' => 'sampleapi@cbc.philrice.gov.ph',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);
        $response->assertStatus(200);
    }
}
