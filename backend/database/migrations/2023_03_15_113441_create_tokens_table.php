<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokensTable extends Migration
{
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->string('access_token');
            $table->string('refresh_token');
            $table->string('expires_in');
            $table->dateTime('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tokens');
    }
}
