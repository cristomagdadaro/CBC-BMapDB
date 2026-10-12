<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crop_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('scientific_name')->nullable();
            $table->string('icon_url')->nullable();
            
            // JSON Schema defining the required attributes for this crop type
            $table->json('attributes_schema')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crop_types');
    }
};
