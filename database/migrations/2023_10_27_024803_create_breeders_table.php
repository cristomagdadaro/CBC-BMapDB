<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\PbMap\Enums\BreederType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('breeders', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->string('suffix')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email');
            $table->enum('breeder_type', [BreederType::PRIVATE->value, BreederType::PUBLIC->value])->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('affiliation')->constrained('institutes');
            $table->string('position')->nullable();
            $table->string('educ_level')->nullable();
            $table->string('expertise')->nullable();
            $table->string('research_interest')->nullable();
            $table->foreignId('geolocation')->constrained('loc_cities');
            $table->longText('photo')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breeders');
    }
};
