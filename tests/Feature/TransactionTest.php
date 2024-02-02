<?php

namespace Tests\Feature;

use App\Models\Inventory\Item;
use App\Models\Inventory\Supplier;
use App\Models\Inventory\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /** @test **/
    public function get_all_transactions(): void
    {
        $response = $this->get('/api/inventory/transactions');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    }

    /** @test **/
    public function get_single_transaction(): void
    {
        $transaction = Transaction::all()->random(1)->first();
        $response = $this->get('/api/inventory/transactions/'.$transaction->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'barcode',
            'item_id',
            'supplier_id',
            'personnel_id',
            'quantity',
            'unit',
            'unit_price',
            'total_cost',
            'transac_type',
            'remarks',
        ]);

        $response->assertJson([
            'id' => $transaction->id,
            'barcode' => $transaction->barcode,
            'item_id' => $transaction->item_id,
            'supplier_id' => $transaction->supplier_id,
            'personnel_id' => $transaction->personnel_id,
            'unit' => $transaction->unit,
            'quantity' => $transaction->quantity,
            'unit_price' => $transaction->unit_price,
            'total_cost' => $transaction->total_cost,
            'transac_type' => $transaction->transac_type,
            'remarks' => $transaction->remarks,
        ]);
    }

    /** @test **/
    public function create_transaction(): void
    {
        $item = Item::factory()->make()->first();
        $response = $this->post('/api/inventory/transactions', [
            'barcode' => 'Test Barcode',
            'item_id' => $item->id,
            'supplier_id' => Supplier::all()->random(1)->first()->id,
            'personnel_id' => 1,
            'quantity' => 1,
            'unit' => 'Test Unit',
            'unit_price' => 1,
            'total_cost' => 1,
            'transac_type' => 'in',
            'remarks' => 'Test Remarks',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'barcode',
            'item_id',
            'supplier_id',
            'personnel_id',
            'quantity',
            'unit',
            'unit_price',
            'total_cost',
            'transac_type',
            'remarks',
        ]);
    }

    /** @test **/
    public function update_transaction(): void
    {
        $transaction = Transaction::all()->random(1)->first();
        $response = $this->put('/api/inventory/transactions/'.$transaction->id, [
            'barcode' => 'CBC-06-217821',
            'item_id' => $transaction->item_id,
            'supplier_id' => $transaction->supplier_id,
            'personnel_id' => $transaction->personnel_id,
            'quantity' => 1,
            'unit' => 'Test Unit',
            'unit_price' => 1,
            'total_cost' => 1,
            'transac_type' => 'in',
            'remarks' => 'Test Remarks',
        ]);

        $response->assertStatus(200);
    }

    /** @test **/
    public function delete_transaction(): void
    {
        $transaction = Transaction::all()->random(1)->first();
        $response = $this->delete('/api/inventory/transactions/'.$transaction->id);

        $response->assertStatus(200);
    }
}
