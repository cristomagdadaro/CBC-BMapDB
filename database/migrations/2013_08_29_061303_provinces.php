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
            $table->string('provDesc')->unique();
            $table->string('regDesc');
            $table->foreign('regDesc')->references('regDesc')->on('loc_regions');
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
