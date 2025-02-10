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
        Schema::create('data_views', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->bigInteger('user_account_id'); //accounts id table
            $table->string('model');
            $table->text('columns')->nullable();
            $table->string('visibility_guard')->default('private');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_account_id', 'model', 'visibility_guard']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_views');
    }
};
