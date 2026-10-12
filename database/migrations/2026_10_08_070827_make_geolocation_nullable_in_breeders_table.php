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
        Schema::table('breeders', function (Blueprint $table) {
            $table->dropForeign(['geolocation']);
            $table->unsignedBigInteger('geolocation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breeders', function (Blueprint $table) {
            $table->unsignedBigInteger('geolocation')->nullable(false)->change();
            $table->foreign('geolocation')->references('id')->on('loc_cities');
        });
    }
};
