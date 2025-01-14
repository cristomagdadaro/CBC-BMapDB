<?php

namespace Tests\Unit;

use App\Models\Breeder;
use Tests\TestCase;

class BreedersCrudTest extends TestCase
{
    /** @test **/
    public function get_all_breeders(): void
    {
        $this->userSetup();
        $response = $this->getJson('/api/breeders/summary?is_exact=true&filter=name&geo_location_filter=region');
        $response->assertStatus(200);

        $this->assertEquals(15, $response['meta']['total']);
    }

    /** @test **/
    public function get_all_breeders_with_appends(): void
    {
        $this->userSetup();
        $response = $this->getJson('/api/breeders?per_page=10&sort=id&order=asc&with=affiliated,location&count=commodities&page=1');
        $response->assertStatus(200);

        $this->assertEquals(15, $response['meta']['total']);
    }

    /** @test **/
    public function get_all_public_breeders(): void
    {
        $this->userSetup();
        $response = $this->getJson('/api/public/breeders/summary?is_exact=true&filter=name&geo_location_filter=region&filter_by_parent_column=breeder_id');
        $response->assertStatus(200);
        print_r($response);
        $this->assertEquals(15, $response['meta']['total']);
    }

    /** @test **/
    public function get_a_specific_breeder(): void
    {
        $this->userSetup();
        $response = $this->getJson('/api/breeders/2');
        print_r($response->collect()->toArray());
        $response->assertStatus(200);
        $this->assertDatabaseHas('breeders', [
            'id' => 2,
            'name' => $response['data']['name'],
            'affiliation' => $response['data']['affiliation'],
        ]);
    }

    /** @test **/
    public function get_a_specific_breeder_with_appended_table(): void
    {
        $this->userSetup();
        $response = $this->getJson('/api/breeders/2?with=affiliated,location&count=commodities');
        print_r($response->collect()->toArray());
        $response->assertStatus(200);
        $this->assertDatabaseHas('breeders', [
            'id' => 2,
            'name' => $response['data']['name'],
            'affiliation' => $response['data']['affiliation'],
        ]);
    }

    /** @test **/
    public function get_a_specific_breeder_that_does_not_exist(): void
    {
        $this->userSetup();
        $response = $this->getJson('/api/breeders/999');
        print_r($response->collect()->toArray());
        $response->assertStatus(404);
    }

    /** @test **/
    public function create_a_breeder(): void
    {
        $this->userSetup();
        $response = $this->postJson('/api/breeders', [
            'user_id' => '1',
            'name' => 'Test Breeder',
            'affiliation' => '1',
            'geolocation' => '2',
            'phone' => 'Test Phone',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        print_r($response->collect()->toArray());

        $response->assertStatus(201);
        $this->assertDatabaseHas('breeders', [
            'id' => $response['data']['id'],
            'user_id' => '1',
            'name' => 'Test Breeder',
            'affiliation' => '1',
            'geolocation' => '2',
            'phone' => 'Test Phone',
            'email' => 'test@gmail.com',
        ]);
    }

    /** @test **/
    public function create_a_breeder_with_invalid_data(): void
    {
        $this->userSetup();
        $response = $this->postJson('/api/breeders', [
            'name' => 'Test Breeder',
            'affiliation' => 'Test Agency',
            'address' => 'Test Address',
            'phone' => 'Test Phone',
            'email' => 'test',
        ]);

        $response->assertStatus(422);
    }

    /** @test **/
    public function create_a_duplicate_breeder(): void
    {
        $this->userSetup();
        $this->postJson('/api/breeders', [
            'user_id' => '1',
            'name' => 'Test Breeder',
            'affiliation' => '1',
            'geolocation' => '2',
            'phone' => 'Test Phone',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response = $this->postJson('/api/breeders', [
            'user_id' => '1',
            'name' => 'Test Breeder',
            'affiliation' => '1',
            'geolocation' => '2',
            'phone' => 'Test Phone',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);
    }

    /** @test **/
    public function delete_a_breeder(): void
    {
        $this->userSetup();
        $response = $this->deleteJson('/api/breeders/1');
        $response->assertStatus(200);
        print_r($response->collect()->toArray());
        $this->assertDatabaseMissing('breeders', [
            'id' => 1,
        ]);
    }

    /** @test **/
    public function delete_multiple_breeder(): void
    {
        $this->userSetup();
        $response = $this->deleteJson(route('api.breeders.destroy.multi'), [
            'ids' => [3, 4, 5],
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('breeders', [
            'id' => [3, 4, 5],
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
        $response = $this->putJson('/api/breeders/2', [
            'user_id' => '1',
            'name' => 'Test Breeder',
            'affiliation' => 'Test Agency',
            'address' => 'Test Address',
            'phone' => 'Test Phone',
            'email' => 'update_email@gmail.com',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('breeders', [
            'id' => 2,
            'name' => 'Test Breeder',
            'affiliation' => 'Test Agency',
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
            'affiliation' => 'Test Agency',
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
            'affiliation' => 'Test Agency',
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
                'affiliation' => 'Test Agency',
                'address' => 'Test Address',
                'phone' => 'Test Phone',
                'email' => 'update_email@gmail.com',
            ]
        );

        $response = $this->putJson('/api/breeders/'. $breeder->id,[
            'name' => 'Test Breeder',
            'affiliation' => 'Test Agency',
            'address' => 'Test Address',
            'phone' => 'Test Phone',
            'email' => 'update_email@gmail.com',
        ]);

        $response->assertStatus(422);
    }

    /** @test **/
    public function get_breeder_with_commodities_table(): void
    {
        $this->userSetup();
        $response = $this->getJson('/api/commodities?filter_by_parent_column=breeder_id&filter_by_parent_id=60&page=1&per_page=10&sort=id&order=asc&with=breeder,location');
        print_r($response->collect()->toArray());
        $response->assertStatus(200);
    }
}
