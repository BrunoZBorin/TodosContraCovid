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

    public function unidade_sintomatica()
    {
        return $this->belongsTo(UnidadeSintomatica::class);
    }
    
    public function unidade_saude()
    {
        return $this->belongsTo(UnidadeSaude::class);
    }

    public function paciente_comorbidades()
    {
        return $this->hasMany(PacienteComorbidades::class);
    }

    public function atendimento()
    {
        return $this->hasMany(Atendimento::class);
    }

    public function familiar()
    {
        return $this->hasMany(Familiar::class);
    }
}