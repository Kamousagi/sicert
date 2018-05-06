<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluacion';
    public $timestamps = false;
    public $primaryKey = "cod_evaluacion";
    
    public function detalle()
    {
        return $this->hasMany('App\EvaluacionDetalle', 'cod_evaluacion');
    }
}