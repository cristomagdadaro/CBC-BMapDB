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
            $table->string('fname', 50);
            $table->string('mname', 50)->nullable();
            $table->string('lname', 50);
            $table->string('suffix', 10)->nullable();
            $table->string('position', 50)->nullable();
            $table->enum('educ_level', ["Bachelor\'s", "Master\'s", "Doctoral"])->nullable();
            $table->string('expertise', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('mobile_no', 50)->nullable();
            $table->timestamps();
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
