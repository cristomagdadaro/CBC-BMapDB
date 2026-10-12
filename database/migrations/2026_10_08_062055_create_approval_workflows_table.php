<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_workflows', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic: This could be a Variety, a Breeder Profile, a TWG Project, etc.
            $table->morphs('morphable');
            
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('reviewer_id')->nullable();
            
            $table->string('status')->default('pending'); // pending, revision, approved, rejected
            
            // The proposed changes (delta) stored in JSON
            $table->json('payload');
            
            $table->text('reviewer_remarks')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_workflows');
    }
};
