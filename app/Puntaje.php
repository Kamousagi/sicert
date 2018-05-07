<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puntaje extends Model
{
    protected $table = 'puntaje';
    public $timestamps = false;
    public $primaryKey = "cod_puntaje";

}