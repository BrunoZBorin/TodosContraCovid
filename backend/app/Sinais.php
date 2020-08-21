<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sinais extends Model
{
    protected $fillable = [
        'nome'
    ];
    public $timestamps = false;

    public function atendimento_sinais()
    {
        return $this->hasMany(AtendimentoSinais::class);
    }
    
}
