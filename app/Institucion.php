<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $table = 'institucion';
    public $timestamps = false;
    public $primaryKey = "cod_institucion";

    public function ugel()
    {
        return $this->belongsTo('App\Ugel');
    }

}