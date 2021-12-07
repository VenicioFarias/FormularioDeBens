<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('nome');
            $table->string('matricula', 8)->unique();
            $table->string('folha', 10)->nullable();
            $table->string('estado_civil', 10);
            $table->string('pai')->nullable();
            $table->string('mae')->nullable();
            $table->string('cpf', 11)->unique();
            $table->string('pis_pasep', 20)->unique()->nullable();
            $table->string('grupo_sanguineo', 3)->nullable();
            $table->date('data_nascimento');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoas');
    }
}
