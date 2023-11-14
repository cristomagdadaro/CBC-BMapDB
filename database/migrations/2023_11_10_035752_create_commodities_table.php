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
            $table->string('name')->unique('unique_commodity_name');
            $table->foreignId('breeder_id')->constrained('breeders');
            $table->string('scientific_name');
            $table->string('variety');
            $table->string('accession');
            $table->string('germplasm');
            $table->double('population');
            $table->string('maturity_period');
            $table->double('yield');
            $table->string('description')->nullable();
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
