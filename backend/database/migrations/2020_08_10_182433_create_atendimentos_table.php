<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtendimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atendimentos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->dateTime('data_hora_ligacao');
            $table->enum('isolamento', ['sim', 'nao']);
            $table->enum('orientacao', ['bem', 'confuso', 'sonolento']);
            $table->enum('apetite', ['bom', 'diminuido', 'anorexico']);
            $table->enum('febre', ['ausente', 'pico_baixo', 'persistente']);
            $table->enum('tosse', ['ausente', 'fala_sem_tossir', 'fala_tossindo']);
            $table->enum('falta_de_ar', ['ausente', 'presente_ao_esforco', 'intensa_no_repouso']);
            $table->string('observacoes_gerais');
            $table->enum('orientacao_conduta', ['manter_isolamento_domiciliar', 'encaminhar_unidade_sintomatica', 'encaminhar_SAMU']);
            
            $table->integer('paciente_id')->unsigned();
            $table->foreign('paciente_id')
            ->references('id')
            ->onDelete('cascade')
            ->on('pacientes');

            $table->integer('usuario_id')->unsigned();
            $table->foreign('usuario_id')
            ->references('id')
            ->onDelete('cascade')
            ->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atendimentos');
    }
}
