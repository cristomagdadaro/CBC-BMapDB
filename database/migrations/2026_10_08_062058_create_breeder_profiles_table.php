<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('breeder_profiles', function (Blueprint $table) {
            $table->id();
            
            // Links to the core user account for login/RBAC
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Multi-tenancy: Which institute does this breeder belong to?
            $table->foreignId('institute_id')->nullable()->constrained('institutes')->nullOnDelete();
            
            $table->string('specialization')->nullable();
            $table->text('biography')->nullable();
            $table->string('photo_path')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('breeder_profiles');
    }
};
