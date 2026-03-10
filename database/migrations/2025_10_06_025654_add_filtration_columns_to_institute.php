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
        Schema::table('institutes', function (Blueprint $table) {
            $table->boolean('ignoreUserBasedFiltration')->default(false)->after('phone');
            $table->boolean('ignoreAffiliationBasedFiltration')->default(false)->after('ignoreUserBasedFiltration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn(['ignoreUserBasedFiltration', 'ignoreAffiliationBasedFiltration']);
        });
    }
};
