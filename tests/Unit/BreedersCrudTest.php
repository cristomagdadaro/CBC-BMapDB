<?php

namespace Tests\Unit;

use App\Models\Breeder;
use App\Models\User;
use Tests\TestCase;

class BreedersCrudTest extends TestCase
{
    protected function userSetup(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /** @test **/
    public function get_all_breeders(): void
    {
        $this->userSetup();
        $response = $this->getJson('/api/breeders');
        $response->assertStatus(200);

        $this->assertEquals(15, $response['meta']['total']);
    }

    /** @test **/
    public function get_a_specific_breeder(): void
    {
        $this->userSetup();
        $response = $this->getJson('/api/breeders/2');
        $response->assertStatus(200);

        $this->assertEquals(2, $response->collect()['id']);
    }

    /** @test **/
    public function get_a_specific_breeder_that_does_not_exist(): void
    {
        $this->userSetup();
        $response = $this->getJson('/api/breeders/999');

        $response->assertStatus(404);
    }

    /** @test **/
    public function create_a_breeder(): void
    {
        $this->userSetup();
        $response = $this->postJson('/api/breeders', [
            'name' => 'Test Breeder',
            'agency' => 'Test Agency',
            'address' => 'Test Address',
            'phone' => 'Test Phone',
            'email' => 'test@gmail.com',
        ]);

        $response->assertStatus(200);
    }

    /** @test **/
    public function create_a_breeder_with_invalid_data(): void
    {
        $this->userSetup();
        $response = $this->postJson('/api/breeders', [
            'name' => 'Test Breeder',
            'agency' => 'Test Agency',
            'address' => 'Test Address',
            'phone' => 'Test Phone',
            'email' => 'test',
        ]);

        $response->assertStatus(422);
    }

    /** @test **/
    public function delete_a_breeder(): void
    {
        $this->userSetup();
        $response = $this->deleteJson('/api/breeders/1');
        $response->assertStatus(200);

        print_r($response->json());

        $this->assertDatabaseMissing('breeders', [
            'id' => 1,
        ]);
    }

    /** @test **/
    public function delete_a_breeder_that_does_not_exist(): void
    {
        $this->userSetup();
        $response = $this->deleteJson('/api/breeders/999');
        $response->assertStatus(404);
    }

    /** @test **/
    public function update_a_breeder(): void
    {
        $this->userSetup();
        $response = $this->putJson('/api/breeders/1', [
            'name' => 'Test Breeder',
            'agency' => 'Test Agency',
            'address' => 'Test Address',
            'phone' => 'Test Phone',
            'email' => 'update_email@gmail.com',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('breeders', [
            'id' => 1,
            'name' => 'Test Breeder',
            'agency' => 'Test Agency',
            'address' => 'Test Address',
            'phone' => 'Test Phone',
            'email' => 'update_email@gmail.com',
            ]);
    }

    /** @test **/
    public function update_a_breeder_with_invalid_data(): void
    {
        $this->userSetup();
        $response = $this->putJson('/api/breeders/1', [
            'name' => 'Test Breeder',
            'agency' => 'Test Agency',
            'address' => 'Test Address',
            'phone' => 'Test Phone',
            'email' => 'update_email',
        ]);

        $response->assertStatus(422);
    }

    /** @test **/
    public function update_a_breeder_that_does_not_exist(): void
    {
        $this->userSetup();
        $response = $this->putJson('/api/breeders/999', [
            'name' => 'Test Breeder',
            'agency' => 'Test Agency',
            'address' => 'Test Address',
            'phone' => 'Test Phone',
            'email' => 'update_email@gmail.com',
        ]);

        $response->assertStatus(404);
    }

    /** @test **/
    public function update_a_user_with_used_email(): void
    {
        $this->userSetup();
        $breeder = Breeder::factory()->create(
            [
                'name' => 'Test Breeder',
                'agency' => 'Test Agency',
                'address' => 'Test Address',
                'phone' => 'Test Phone',
                'email' => 'update_email@gmail.com',
            ]
        );

        $response = $this->putJson('/api/breeders/'. $breeder->id,[
            'name' => 'Test Breeder',
            'agency' => 'Test Agency',
            'address' => 'Test Address',
            'phone' => 'Test Phone',
            'email' => 'update_email@gmail.com',
        ]);

        $response->assertStatus(422);
    }
}
