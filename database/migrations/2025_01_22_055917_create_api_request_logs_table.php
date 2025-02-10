<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiRequestLogsTable extends Migration
{
    public function up()
    {
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_role')->nullable();
            $table->string('method')->nullable();
            $table->longText('url')->nullable();
            $table->string('model')->nullable();
            $table->text('data')->nullable();
            $table->unsignedBigInteger('modified_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_request_logs');
    }
}
