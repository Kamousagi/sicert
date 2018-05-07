<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsolidadoCabeza extends Model
{
    protected $table = 'consolidado_cabeza';
    public $timestamps = false;
    public $primaryKey = "cod_consolidado_cabeza";
    
    public function consolidado()
    {
        return $this->belongsTo('App\Consolidado');
    }

    public function cuerpo()
    {
        return $this->hasMany('App\ConsolidadoCuerpo', 'cod_consolidado_cabeza');
    }

}