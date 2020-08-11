<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacienteComorbidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paciente_comorbidades', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('paciente_id')->unsigned();
            $table->foreign('paciente_id')
            ->references('id')
            ->onDelete('cascade')
            ->on('pacientes');

            $table->integer('comorbidades_id')->unsigned();
            $table->foreign('comorbidades_id')
            ->references('id')
            ->onDelete('cascade')
            ->on('comorbidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paciente_comorbidades');
    }
}
