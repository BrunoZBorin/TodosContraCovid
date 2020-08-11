<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    protected $fillable = [
        'isolamento', 'orientacao', 'apetite', 'febre', 'tosse', 'falta_de_ar', 'observacoes_gerais',
        'orientacao_conduta', 'paciente_id', 'usuario_id', 'data_hora_ligacao'
    ];
    public $timestamps = false;
}
