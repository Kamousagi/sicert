<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consolidado extends Model
{
    protected $table = 'consolidado';
    public $timestamps = false;
    public $primaryKey = "cod_consolidado";
    
    public function cabeza()
    {
        return $this->hasMany('App\ConsolidadoCabeza', 'cod_consolidado');
    }
}