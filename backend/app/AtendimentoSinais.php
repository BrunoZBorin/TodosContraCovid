<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtendimentoSinais extends Model
{
    protected $fillable = [
        'atendimento_id', 'sinais_id'
    ];
    public $timestamps = false;

    public function sinais()
    {
        return $this->belongsTo(Sinais::class);
    }
    
    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class);
    }
}
