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
        Schema::create('twg_expert', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name')->unique();
            $table->string('position');
            $table->enum('educ_level', ["Bachelor\'s", "Master\'s", "Doctoral"])->nullable();
            $table->string('expertise')->nullable();
            $table->string('research_interest')->nullable();
            $table->string('mobile')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twg_expert');
    }
};
