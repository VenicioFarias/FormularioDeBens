<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotacoes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pessoa_id');
            $table->foreign('pessoa_id')->references('id')->on('pessoas');
            $table->date('ato_ini');
            $table->date('ato_fim')->nullable();
            $table->date('diario_oficial_ini')->nullable();
            $table->date('diario_oficial_fim')->nullable();
            $table->unsignedInteger('pagina_ini')->nullable();
            $table->unsignedInteger('pagina_fim')->nullable();
            $table->date('exercicio_ini')->nullable();
            $table->date('exercicio_fim')->nullable();
            $table->text('observacao', 400)->nullable();
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
        Schema::dropIfExists('lotacoes');
    }
}
