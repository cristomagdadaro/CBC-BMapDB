<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locationables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            $table->morphs('locationable');
            $table->timestamps();
            
            // A model should only be tagged to a specific location once
            $table->unique(['location_id', 'locationable_id', 'locationable_type'], 'locationable_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locationables');
    }
};
