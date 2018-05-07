<?php

namespace App\Http\Controllers;

use App\Evaluacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EstadisticaDetalladoModelo {
    public $n;
    public $institucion;
    public $nalumnos;
    public $n1;
    public $n2;
    public $n3;
    public $n4;
    public $p1;
    public $p2;
    public $p3;
    public $p4;
}

class EstadisticaResumenModelo {
    public $n;
    public $ugel;
    public $nalumnos;
    public $n1;
    public $n2;
    public $n3;
    public $n4;
    public $p1;
    public $p2;
    public $p3;
    public $p4;
}

class EstadisticaPreguntasModelo {
    public $pregunta;
    public $p1;
    public $p2;
    public $p3;
    public $p4;
    public $p5;
    public $p6;
    public $p7;
    public $p8;
    public $p9;
    public $p10;
    public $p11;
    public $p12;
    public $p13;
    public $p14;
    public $p15;
    public $p16;
    public $p17;
    public $p18;
    public $p19;
    public $p20;
    public $p21;
    public $p22;
    public $p23;
    public $p24;
    public $p25;    
}

class ReporteController extends Controller
{
    public function cronograma_evaluacion()
    {        
        $evaluaciones = Evaluacion::all();
        return view('aplicacion.reportes.cronograma_evaluacion', ['evaluaciones' => $evaluaciones]);
    }
    public function estadistica_detallado()
    {
        $cod_eval = Input::get('cod_eval', 0);
        $cod_ugel = Input::get('cod_ugel', 0);
        $resultados = new EstadisticaDetalladoModelo();
        return view('aplicacion.reportes.estadistica_detallado', 
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_eval, 'ugel_seleccionada' => $cod_ugel]);
    }
    public function estadistica_preguntas()
    {        
        $cod_eval = Input::get('cod_eval', 0);
        $resultados = new EstadisticaPreguntasModelo();        
        return view('aplicacion.reportes.estadistica_preguntas', 
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_eval]);
    }
    public function estadistica_resumen()
    {
        $cod_eval = Input::get('cod_eval', 0);
        $resultados = new EstadisticaResumenModelo();
        return view('aplicacion.reportes.estadistica_resumen', 
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_eval]);
    }
}
