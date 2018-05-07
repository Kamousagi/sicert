<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsolidadoCuerpo extends Model
{
    protected $table = 'consolidado_cuerpo';
    public $timestamps = false;
    public $primaryKey = "cod_consolidado_cuerpo";

    public function cabeza()
    {
        return $this->belongsTo('App\ConsolidadoCabeza');
    }
    
    public function detalle()
    {
        return $this->hasMany('App\ConsolidadoCuerpo', 'cod_consolidado_cuerpo');
    }
}