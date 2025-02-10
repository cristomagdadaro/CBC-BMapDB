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
            $table->foreignId('institution')->nullable()->constrained('institutes')->nullOnDelete();
            $table->text('title')->nullable();
            $table->longText('objective')->nullable();
            $table->text('expected_output')->nullable();
            $table->text('project_leader')->nullable();
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
