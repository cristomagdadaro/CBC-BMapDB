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
        Schema::create('twg_product', function(Blueprint $table){
            $table->id();
            $table->foreignId('twg_expert_id')->nullable()->constrained('twg_expert');
            $table->string('name', 50);
            $table->string('brand', 50)->nullable();
            $table->string('purpose', 255)->nullable();
            $table->string('cost', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twg_product');
    }
};
