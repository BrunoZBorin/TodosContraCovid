<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PacienteComorbidades extends Model
{
    protected $fillable = [
        'paciente_id', 'comorbidades_id'
    ];
    public $timestamps = false;
}
