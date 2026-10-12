<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institute_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institute_id')->constrained('institutes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('active'); // active, pending
            $table->timestamps();
            
            $table->unique(['institute_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institute_user');
    }
};
