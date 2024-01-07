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
        Schema::create('twg_service', function(Blueprint $table){
            $table->id();
            $table->foreignId('twg_expert_id')->nullable()->constrained('twg_expert');
            $table->string('type', 255)->nullable();
            $table->string('purpose', 255)->nullable();
            $table->string('direct_beneficiaries', 255)->nullable();
            $table->string('indirect_beneficiaries', 255)->nullable();
            $table->string('officer_in_charge', 255)->nullable();
            $table->string('cost', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twg_service');
    }
};
