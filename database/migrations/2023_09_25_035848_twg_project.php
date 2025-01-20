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
        Schema::create('twg_project', function (Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('twg_expert_id')->nullable()->constrained('twg_expert');
            $table->string('title', 255)->nullable();
            $table->longText('objective')->nullable();
            $table->string('expected_output', 255)->nullable();
            $table->string('project_leader', 255)->nullable();
            $table->string('funding_agency', 255)->nullable();
            $table->string('duration', 255)->nullable();
            $table->enum('status', ['Active', 'Completed', 'Cancelled', 'On Hold'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twg_project');
    }
};
