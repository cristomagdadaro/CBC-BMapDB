<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    /** @test **/
    public function get_all_suppliers(): void
    {
        $response = $this->get('/api/inventory/suppliers');
        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    }

    /** @test **/
    public function get_supplier_by_id(): void
    {
        $response = $this->get('/api/inventory/suppliers/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'phone',
            'address',
            'created_at',
            'updated_at',
        ]);
    }

    /** @test **/
    public function create_supplier(): void
    {
        $response = $this->post('/api/inventory/suppliers', [
            'name' => 'Supplier 1',
            'email' => 'supplier@gmail.com',
            'phone' => '1234567890',
            'address' => '123, Supplier Street, Supplier City',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'phone',
            'address',
            'created_at',
            'updated_at',
        ]);
    }

    /** @test **/
    public function update_supplier(): void
    {
        $response = $this->put('/api/inventory/suppliers/1', [
            'name' => 'Supplier 1',
            'email' => 'updated@email.com',
            'phone' => '1234567890',
            'address' => '123, Supplier Street, Supplier City',
        ]);

        $response->assertStatus(200);
        $this->assertTrue($response->json() === 1);
    }

    /** @test **/
    public function delete_supplier(): void
    {
        $response = $this->delete('/api/inventory/suppliers/1');
        $response->assertStatus(200);
        $this->assertTrue($response->json() === 1);
    }
}
