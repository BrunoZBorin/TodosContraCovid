<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadeSaude extends Model
{
    protected $fillable = [
        'nome', 'cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado'
    ];
    public $timestamps = false;
}
