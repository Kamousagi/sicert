<?php

namespace App;

use App\Evaluacion;
use Illuminate\Database\Eloquent\Model;

class EvaluacionDetalle extends Model
{
    protected $table = 'evaluacion_detalle';
    public $timestamps = false;
    public $primaryKey = "cod_evaluacion_detalle";

    public function evaluacion()
    {
        return $this->belongsTo('App\Evaluacion', 'cod_evaluacion');
    }
}