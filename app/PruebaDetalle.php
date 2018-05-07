<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PruebaDetalle extends Model
{
    protected $table = 'prueba_detalle';
    public $timestamps = false;
    public $primaryKey = 'cod_prueba_detalle';
    
    public function prueba()
    {
        return $this->belongsTo('App\Prueba');
    }
}