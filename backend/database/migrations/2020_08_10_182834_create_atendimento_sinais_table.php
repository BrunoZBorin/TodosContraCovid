<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtendimentoSinaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atendimento_sinais', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('atendimento_id')->unsigned();
            $table->foreign('atendimento_id')
            ->references('id')
            ->onDelete('cascade')
            ->on('atendimentos');

            $table->integer('sinais_id')->unsigned();
            $table->foreign('sinais_id')
            ->references('id')
            ->onDelete('cascade')
            ->on('sinais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atendimento_sinais');
    }
}
