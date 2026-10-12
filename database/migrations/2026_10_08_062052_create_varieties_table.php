<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('varieties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_type_id')->constrained('crop_types')->cascadeOnDelete();
            
            // Multi-tenancy: Which institute owns this variety data?
            $table->foreignId('institute_id')->nullable()->constrained('institutes')->nullOnDelete();
            
            $table->string('name');
            $table->string('status')->default('pending'); // pending, approved, rejected
            
            // The actual traits for this variety, conforming to the crop_type's attributes_schema
            $table->json('attributes')->nullable();
            
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('varieties');
    }
};
