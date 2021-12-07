<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAparelhoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aparelho', function (Blueprint $table) {
            $table->id();
            $table->string('imei1');
            $table->string('imei2');
            $table->string('numero_serie');
            $table->string('numero_tombo');
            $table->string('serie_terminal');
            $table->string('issi_terminal');
            $table->foreignId('id_modelo');
            $table->foreignId('id_tipo');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aparelho');
    }
}
