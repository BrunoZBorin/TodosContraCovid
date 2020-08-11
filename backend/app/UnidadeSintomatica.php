<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadeSintomatica extends Model
{
    protected $fillable = [
        'nome' ,'telefone', 'cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado'
    ];
    public $timestamps = false;
}
