<?php

namespace Tests\Unit;

use App\Models\Accounts;
use App\Models\Application;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class AccountCrudTest extends TestCase
{
    /** @test **/
    public function get_all_applications(): void
    {
        $response = $this->getJson('/api/accounts');

        $response->assertStatus(200);
        $this->assertEquals(147, $response['meta']['total']);
    }

    /** @test **/
    public function get_a_applications_by_id(): void
    {
        $response = $this->get('/api/accounts/2');

        $response->assertStatus(200);
        $this->assertEquals(2, $response['id']);
    }

    /** @test **/
    public function get_a_applications_by_id_not_found(): void
    {
        $response = $this->get('/api/accounts/999');

        $response->assertStatus(404);
    }

    /** @test **/
    public function create_a_applications(): void
    {
        $user = User::factory()->create();
        $app = $this->postJson('/api/applications', [
            'name' => 'Test',
            'description' => 'test',
            'url' => 'https://test.com',
            'icon' => 'https://test.com/icon.png',
        ]);

        $uuid = Uuid::uuid4();

        $response = $this->post('/api/accounts', [
            'user_id' => $user->id,
            'app_id' => $app['id'],
            'account_id' => $uuid->toString(),
        ]);

        $response->assertStatus(201);
        $this->assertEquals($uuid->toString(), $response['account_id']);
    }

    /** @test **/
    public function create_a_applications_with_existing_account_id(): void
    {
        $user = User::factory()->create();
        $app = $this->postJson('/api/applications', [
            'name' => 'Test',
            'description' => 'test',
            'url' => 'https://test.com',
            'icon' => 'https://test.com/icon.png',
        ]);

        $uuid = Uuid::uuid4();

        $newAccount = Accounts::factory()->create([
            'user_id' => $user->id,
            'app_id' => $app['id'],
            'account_id' => $uuid->toString(),
        ]);

        $response = $this->postJson('/api/accounts', [
            'user_id' => $user->id,
            'app_id' => $app['id'],
            'account_id' => $uuid->toString(),
        ]);

        $response->assertStatus(422);
    }

    /** @test **/
    public function update_a_applications(): void
    {
        $user = User::factory()->create();
        $app = Application::factory()->create();

        $uuid = Uuid::uuid4();

        $newAccount = Accounts::factory()->create();

        $response = $this->putJson('/api/accounts/'.$newAccount->id, [
            'user_id' => $user->id,
            'app_id' => $app->id,
            'account_id' => $uuid->toString(),
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('accounts', [
            'account_id' => $uuid->toString(),
        ]);
    }

    /** @test **/
    public function update_a_applications_with_existing_account_id(): void
    {
        $user = User::factory()->create();
        $app = Application::factory()->create();

        $uuid = Uuid::uuid4();

        $newAccount = Accounts::factory()->create();

        $response = $this->putJson('/api/accounts/'.$newAccount->id, [
            'user_id' => $user->id,
            'app_id' => $app->id,
            'account_id' => $newAccount->account_id,
        ]);

        $response->assertStatus(422);
    }

    /** @test **/
    public function delete_a_applications(): void
    {
        $newAccount = Accounts::factory()->create();

        $response = $this->deleteJson('/api/accounts/'.$newAccount->id);

        $response->assertStatus(200);
        $this->assertSoftDeleted('accounts', $newAccount->toArray());
    }

    /** @test **/
    public function delete_a_applications_not_found(): void
    {
        $response = $this->deleteJson('/api/accounts/999');

        $response->assertStatus(404);
    }
}