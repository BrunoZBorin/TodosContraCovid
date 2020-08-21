<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadeSaude extends Model
{
    protected $fillable = [
        'nome' ,'telefone', 'cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado'
    ];
    public $timestamps = false;
    
    public function paciente()
    {
        return $this->hasMany(Paciente::class);
    }
    
    public function users()
    {
        return $this->hasMany(Users::class);
    }
    
}
