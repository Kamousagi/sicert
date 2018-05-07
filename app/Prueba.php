<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{
    protected $table = 'prueba';
    public $timestamps = false;
    public $primaryKey = "cod_prueba";
    
    public function detalle()
    {
        return $this->hasMany('App\PruebaDetalle', 'cod_prueba');
    }
}