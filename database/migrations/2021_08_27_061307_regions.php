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
        Schema::create('loc_regions', function (Blueprint $table) {
            $table->id();
            $table->string('psgcCode')->unique();
            $table->string('regDesc')->unique();
            $table->string('regCode')->unique();
            $table->foreignId('country_id')->constrained('loc_countries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loc_regions');
    }
};
