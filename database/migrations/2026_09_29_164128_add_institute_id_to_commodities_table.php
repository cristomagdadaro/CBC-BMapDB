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
        Schema::table('commodities', function (Blueprint $table) {
            $table->foreignId('institute_id')->nullable()->constrained('institutes')->nullOnDelete();
        });

        // Make breeder_id nullable (raw query used to avoid doctrine/dbal version issues)
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE commodities MODIFY breeder_id bigint unsigned NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commodities', function (Blueprint $table) {
            $table->dropForeign(['institute_id']);
            $table->dropColumn('institute_id');
        });

        \Illuminate\Support\Facades\DB::statement('ALTER TABLE commodities MODIFY breeder_id bigint unsigned NOT NULL');
    }
};
