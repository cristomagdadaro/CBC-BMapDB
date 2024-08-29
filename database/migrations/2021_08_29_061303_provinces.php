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
        Schema::create('loc_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('psgcCode')->unique();
            $table->string('provDesc')->unique();
            $table->foreignId('regCode')->constrained('loc_regions');
            $table->string('provCode')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loc_provinces');
    }
};
