<?php

namespace App\Http\Controllers;

use App\Evaluacion;
use App\Ugel;
use App\Consolidado;
use App\ConsolidadoCabeza;
use App\ConsolidadoCuerpo;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use DB;

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
    public function estadistica_detallado(Request $request)
    {
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
            ->pluck('descripcion', 'cod_evaluacion');
        $ugeles = Ugel::select(
            'nom_ugel','cod_ugel')
            ->pluck('nom_ugel', 'cod_ugel');
        $cod_evaluacion = $request->input('cod_evaluacion');
        $cod_ugel = $request->input('cod_ugel');
        $resultados = new EstadisticaDetalladoModelo();
        return view('aplicacion.reportes.estadistica_detallado', 
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'ugel_seleccionada' => $cod_ugel,
            'evaluaciones' => $evaluaciones, 'ugeles' => $ugeles]);
    }
    public function estadistica_preguntas(Request $request)
    {
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
            ->pluck('descripcion', 'cod_evaluacion');
        $cod_evaluacion = $request->input('cod_evaluacion');
        $resultados = new EstadisticaPreguntasModelo();
        return view('aplicacion.reportes.estadistica_preguntas', 
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'evaluaciones' => $evaluaciones]);
    }
    
    public function estadistica_resumen(Request $request)
    {
        //$evaluaciones = Evaluacion::all();
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
            ->pluck('descripcion', 'cod_evaluacion');
        $cod_evaluacion = $request->input('cod_evaluacion');
        $resultados = collect();
        if ($cod_evaluacion>0){
            $consolidado = Consolidado::where('cod_evaluacion',$cod_evaluacion);
            $resultados0 = DB::table('consolidado')
            ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')            
            ->selectraw('count(*) as nalumnos, consolidado.nom_ugel as ugel')
            ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
            ->groupBy('consolidado.nom_ugel')
            ->get();
            $num = 1;
            foreach($resultados0 as $resultado0){
                $resultado = new EstadisticaResumenModelo();
                $resultado1 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n1')
                ->where(['consolidado.cod_evaluacion','=',$cod_evaluacion],['consolidado.ugel','=',$resultado0->ugel],
                ['consolidado_cabeza.nom_comentario','=','PREVIO AL INICIO'])                
                ->get();
                $resultado2 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza.', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as n2')
                ->where(['consolidado.cod_evaluacion','=',$cod_evaluacion],['consolidado.ugel','=',$resultado0->ugel],
                ['consolidado_cabeza.nom_comentario','=','INICIO'])                
                ->get();
                $resultado3 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n3')
                ->where(['consolidado.cod_evaluacion','=',$cod_evaluacion],['consolidado.ugel','=',$resultado0->ugel],
                ['consolidado_cabeza.nom_comentario','=','EN PROCESO'])                
                ->get();
                $resultado4 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n4')
                ->where(['consolidado.cod_evaluacion','=',$cod_evaluacion],['consolidado.ugel','=',$resultado0->ugel],
                ['consolidado_cabeza.nom_comentario','=','LOGRO PREVISTO'])                
                ->get();
                $resultado->n=$num;
                $resultado->ugel=$resultado0->ugel;
                $resultado->nalumnos=$resultado0->nalumnos;
                $resultado->n1=$resultado1->n1;
                $resultado->n2=$resultado2->n2;
                $resultado->n3=$resultado3->n3;
                $resultado->n4=$resultado4->n4;
                $resultado->p1=$resultado->n1/$resultado->nalumnos;
                $resultado->p2=$resultado->n2/$resultado->nalumnos;
                $resultado->p3=$resultado->n3/$resultado->nalumnos;
                $resultado->p4=$resultado->n4/$resultado->nalumnos;
                $num++;
                $resultados->push($resultado);                
            }
        }
        return view('aplicacion.reportes.estadistica_resumen', 
            ['resultados' => $resultados, 'evaluacion_seleccionada' => $cod_evaluacion, 'evaluaciones' => $evaluaciones]);
    }
}