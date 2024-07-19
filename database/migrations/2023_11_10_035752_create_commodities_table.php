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
        Schema::create('commodities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique('unique_commodity_name')->nullable();
            $table->foreignId('breeder_id')->constrained('breeders');
            $table->string('scientific_name')->nullable();
            $table->string('variety')->nullable();
            $table->string('accession')->nullable();
            $table->string('germplasm')->nullable();
            $table->double('population')->nullable();
            $table->string('maturity_period')->nullable();
            $table->double('yield')->nullable();
            $table->string('description')->nullable()->nullable();
            $table->binary('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodities');
    }
};
