<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'nome' ,'telefone', 'cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado',
        'cns', 'data_nasc', 'obito', 'primeira_avaliacao_medica', 'isolamento_ate', 'data_inicio_sintomas',
        'data_coleta_exames', 'unidade_sintomatica_id', 'convenio', 'unidade_saude_id', 'tipo_exame',
        'data_resultado', 'resultado_exame', 'grupo_risco'
    ];
    public $timestamps = false;
}