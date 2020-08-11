<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familiars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('sintomatico');
            $table->enum('exame', ['positivo', 'negativo', 'aguardando_resultado']);
            $table->integer('paciente_id')->unsigned();
            $table->foreign('paciente_id')
            ->references('id')
            ->onDelete('cascade')
            ->on('pacientes');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('familiars');
    }
}
