<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comorbidades extends Model
{
    protected $fillable = [
        'nome'
    ];
    public $timestamps = false;
}
