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
            $table->string('cityDesc');
            $table->string('provDesc');
            $table->string('regDesc');

            $table->foreign('provDesc')->references('provDesc')->on('loc_provinces');
            $table->foreign('regDesc')->references('regDesc')->on('loc_regions');


            $table->unique(['cityDesc', 'provDesc', 'regDesc']);
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
