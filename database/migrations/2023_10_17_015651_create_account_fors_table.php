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
        Schema::create('account_for', function (Blueprint $table) {
            $table->id();
            $table->uuid('account_id')->index()->nullable();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('app_id')->constrained('applications', 'id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_for');
    }
};