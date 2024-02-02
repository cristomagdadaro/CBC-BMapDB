<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('barcode');
            $table->foreignUuid('item_id')->references('id')->on('items');
            $table->enum('transac_type', ['in', 'out']);
            $table->integer('quantity');
            $table->string('unit')->nullable();
            $table->string('unit_price');
            $table->decimal('total_cost', 8, 2);
            $table->foreignId('personnel_id')->references('id')->on('personnels');
            $table->foreignId('supplier_id')->references('id')->on('suppliers');
            $table->date('expiration')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
