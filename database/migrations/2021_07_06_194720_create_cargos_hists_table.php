<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargosHistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargos_hists', function (Blueprint $table) {

                $table->bigIncrements('id');
                $table->unsignedBigInteger('cargo_id');
                $table->foreign('cargo_id')->references('id')->on('cargos');
                $table->unsignedBigInteger('pessoa_id');
                $table->foreign('pessoa_id')->references('id')->on('pessoas');
                $table->string('carater', 20)->nullable();
                $table->date('ato')->nullable();
                $table->string('diario_oficial', 10)->nullable();
                $table->string('fundamento_legal', 100)->nullable();
                $table->date('posse')->nullable();
                $table->date('exercicio')->nullable();
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
        Schema::dropIfExists('cargos_hists');
    }
}
