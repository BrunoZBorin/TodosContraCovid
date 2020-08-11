<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->date('data_nasc');
            $table->string('cns');
            $table->string('logradouro');
            $table->string('numero');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->string('cep');
            $table->string('telefone');
            $table->date('obito');
            $table->date('primeira_avalicao_medica');
            $table->date('isolamento_ate');
            $table->date('data_inicio_sintomas');
            $table->date('data_coleta_exames');
            
            $table->integer('unidade_sintomatica_id')->unsigned();
            $table->foreign('unidade_sintomatica_id')
            ->references('id')
            ->onDelete('cascade')
            ->on('unidade_sintomaticas');
            
            $table->enum('convenio', ['SUS', 'particular']);

            $table->integer('unidade_saude_id')->unsigned();
            $table->foreign('unidade_saude_id')
            ->references('id')
            ->onDelete('cascade')
            ->on('unidade_saudes');
            
            $table->enum('tipo_exame', ['PCR-RT', 'sorologia', 'teste_rapido']);
            $table->date('data_resultado');
            $table->enum('resultado_exame', ['positivo', 'negativo', 'aguardando_resultado']);
            $table->enum('grupo_risco', ['sim', 'nao']);
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
        Schema::dropIfExists('pacientes');
    }
}
