<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comorbidades extends Model
{
    protected $fillable = [
        'nome'
    ];
    public $timestamps = false;

    public function paciente_comorbidades()
    {
        return $this->hasMany(Paciente::class);
    }
}
