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
        Schema::create('geodata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('breeder_id')->constrained('breeders')->onDelete('cascade');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('postal_code');
            $table->string('formatted_address');
            $table->string('place_id');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geodata');
    }
};
