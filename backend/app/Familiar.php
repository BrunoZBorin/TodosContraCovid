<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Familiar extends Model
{
    protected $fillable = [
        'nome', 'sintomatico', 'exame', 'paciente_id'
    ];
    public $timestamps = false;   
}
