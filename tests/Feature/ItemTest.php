<?php

namespace Tests\Feature;

use App\Models\Inventory\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemTest extends TestCase
{
    /** @test **/
    public function get_all_items(): void
    {
        $response = $this->get('/api/inventory/items');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    }

    /** @test **/
    public function get_single_item(): void
    {
        $item = Item::all()->random(1)->first();
        $response = $this->get('/api/inventory/items/'.$item->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'brand',
            'description',
            'category_id',
            'image',
        ]);

        $response->assertJson([
            'id' => $item->id,
            'name' => $item->name,
            'brand' => $item->brand,
            'description' => $item->description,
            'category_id' => $item->category_id,
            'image' => $item->image,
        ]);
    }

    /** @test **/
    public function create_item(): void
    {
        $response = $this->post('/api/inventory/items', [
            'name' => 'Test Item',
            'brand' => 'Test Brand',
            'description' => 'Test Description',
            'category_id' => 1,
            'image' => 'Test Image',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'brand',
            'description',
            'category_id',
            'image',
        ]);
    }

    /** @test **/
    public function update_item(): void
    {
        $item = Item::all()->random(1)->first();
        $response = $this->put('/api/inventory/items/'.$item->id, [
            'name' => 'Updated Test Item',
            'brand' => 'Test Brand',
            'description' => 'Test Description',
            'category_id' => 1,
            'image' => 'Test Image',
        ]);

        $response->assertStatus(200);
    }

    /** @test **/
    public function delete_item(): void
    {
        $item = Item::all()->random(1)->first();
        $response = $this->delete('/api/inventory/items/'.$item->id);

        $response->assertStatus(200);
    }
}
