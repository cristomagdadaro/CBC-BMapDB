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
        Schema::create('loc_cities', function (Blueprint $table) {
            $table->id();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('psgcCode')->unique();
            $table->string('citymunDesc');
            $table->string('citymunCode')->unique();
            $table->string('provCode');

            $table->foreignId('regDesc')->constrained('loc_regions');


            $table->unique(['citymunDesc', 'provCode']);
            $table->foreign('provCode')->references('provCode')->on('loc_provinces');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loc_cities');
    }
};
