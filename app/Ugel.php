<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ugel extends Model
{
    protected $table = 'ugel';
    public $timestamps = false;
    public $primaryKey = "cod_ugel";

    public function instituciones()
    {
        return $this->hasMany('App\Institucion', 'cod_ugel');
    }

}