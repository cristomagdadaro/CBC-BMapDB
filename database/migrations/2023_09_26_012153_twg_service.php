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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('twg_expert_id')->nullable()->constrained('twg_expert');
            $table->string('type', 255)->nullable();
            $table->longText('purpose')->nullable();
            $table->longText('direct_beneficiaries')->nullable();
            $table->longText('indirect_beneficiaries')->nullable();
            $table->longText('officer_in_charge')->nullable();
            $table->longText('cost')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
