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
        Schema::create('commodity_characteristics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commodity_id')->unique()->constrained('commodities')->onDelete('cascade');
            // Commodity Information
            $table->float('weight', 8, 2)->nullable()->comment('Weight in grams (for fruits)');
            $table->float('length', 8, 2)->nullable()->comment('Length in cm (for fruits)');
            $table->float('width', 8, 2)->nullable()->comment('Width in cm (for fruits)');
            $table->string('shape')->nullable()->comment('Shape (general)');

            // Fruit Characteristics
            $table->string('skin_color')->nullable()->comment('Fruit skin or peel color');
            $table->string('skin_texture')->nullable()->comment('Fruit skin or peel texture');
            $table->string('flesh_color')->nullable()->comment('Fruit flesh color');
            $table->string('flesh_texture')->nullable()->comment('Fruit flesh texture');
            $table->string('flesh_flavor')->nullable()->comment('Fruit flesh flavor');
            $table->string('aroma')->nullable()->comment('Fruit aroma');

            // Root Characteristics
            $table->string('root_flesh_color')->nullable()->comment('Root flesh color');
            $table->string('root_cortex_color')->nullable()->comment('Root cortex color');
            $table->string('root_skin_color')->nullable()->comment('Root skin color');
            $table->string('root_shape')->nullable()->comment('Root shape');

            // Tuber Characteristics
            $table->string('tuber_flesh_color')->nullable()->comment('Tuber flesh color');
            $table->string('tuber_cortex_color')->nullable()->comment('Tuber cortex color');
            $table->string('tuber_skin_color')->nullable()->comment('Tuber skin color');
            $table->string('tuber_shape')->nullable()->comment('Tuber shape');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodity_characteristics');
    }
};
