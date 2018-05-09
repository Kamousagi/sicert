<?php

namespace App\Http\Controllers;

use App\Evaluacion;
use App\Ugel;
use App\Institucion;
use App\Consolidado;
use App\ConsolidadoCabeza;
use App\ConsolidadoCuerpo;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use DB;
use Fx3costa\LaravelChartJs\Providers\ChartjsServiceProvider;

class EstadisticaDetalladoModelo {
    public $n;
    public $cod_institucion;
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
    public $cod_ugel;
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

class ResumenPreguntasModelo {
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

class EstadisticaSeccionModelo {
    public $n;
    public $seccion;
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

class EstadisticaAlumnoModelo {
    public $n;
    public $alumno;
    public $nota;
    public $nivel;
}

class EstadisticaPreguntaModelo {
    public $pregunta;
    public $respuesta;
    public $comentario;
}

class ReporteController extends Controller
{
    public function cronograma_evaluacion()
    {        
        $evaluaciones = DB::table('evaluacion')->select(
        DB::raw("(substr(fec_fecha,1,10)) AS fec_fecha"),
        'num_correlativo','num_anio',
        DB::raw("(CASE WHEN num_tipo = 1 THEN 'MATEMATICA' WHEN num_tipo = 2 THEN 'COMUNICACION' ELSE 'CTA' END) AS num_tipo"),
        DB::raw("(CASE WHEN num_grado = 1 THEN '2° DE PRIMARIA' WHEN num_grado = 2 THEN '4° DE PRIMARIA' ELSE '2° DE SECUNDARIA' END) AS num_grado"))
        ->get();
        //die($evaluaciones);
        return view('aplicacion.reportes.cronograma_evaluacion', ['evaluaciones' => $evaluaciones]);
    }

    public function estadistica_pregunta(Request $request)
    {
        $cod_evaluacion = $request->input('cod_evaluacion');
        $cod_ugel = $request->input('cod_ugel');
        $cod_institucion = $request->input('cod_institucion');        
        $nom_seccion = $request->input('nom_seccion');
        $nom_alumno = $request->input('nom_alumno');
        $nom_evaluacion = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS nom_evaluacion"))
            ->where('cod_evaluacion','=',$cod_evaluacion)->first()->nom_evaluacion;
        $nom_ugel = Ugel::where('cod_ugel','=',$cod_ugel)->first()->nom_ugel;
        $nom_institucion = Institucion::where('cod_institucion','=',$cod_institucion)->first()->nom_institucion;        
        $grafico = new EstadisticaAlumnoModelo();
        if ($cod_evaluacion>0){
            $resultados = DB::table('consolidado')
            ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
            ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
            ->select('consolidado_cuerpo.num_pregunta as pregunta','consolidado_cuerpo.num_respuesta as respuesta','consolidado_cuerpo.nom_comentario as comentario')
            ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
            ->where('consolidado.cod_ugel','=',$cod_ugel)
            ->where('consolidado.cod_institucion', '=', $cod_institucion)
            ->where('consolidado_cabeza.nom_seccion', '=', $nom_seccion)
            ->where('consolidado_cabeza.nom_alumno', '=', $nom_alumno)
            ->orderBy('consolidado_cuerpo.num_pregunta')
            ->get();
        }
        return view('aplicacion.reportes.estadistica_pregunta',
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'nom_evaluacion_seleccionada' => $nom_evaluacion,
            'ugel_seleccionada' => $cod_ugel, 'nom_ugel_seleccionada' => $nom_ugel,
            'institucion_seleccionada' => $cod_institucion, 'nom_institucion_seleccionada' => $nom_institucion,
            'seccion_seleccionada' => $nom_seccion, 'alumno_seleccionado' => $nom_alumno]);
    }

    public function estadistica_alumno(Request $request)
    {
        $cod_evaluacion = $request->input('cod_evaluacion');
        $cod_ugel = $request->input('cod_ugel');
        $cod_institucion = $request->input('cod_institucion');
        $nom_seccion = $request->input('nom_seccion');
        $nom_evaluacion = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS nom_evaluacion"))
            ->where('cod_evaluacion','=',$cod_evaluacion)->first()->nom_evaluacion;
        $nom_ugel = Ugel::where('cod_ugel','=',$cod_ugel)->first()->nom_ugel;
        $nom_institucion = Institucion::where('cod_institucion','=',$cod_institucion)->first()->nom_institucion;
        $resultados = [];
        $grafico = new EstadisticaAlumnoModelo();
        if ($cod_evaluacion>0){
            $resultados0 = DB::table('consolidado')
            ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')            
            ->selectraw('sum(consolidado_cabeza.num_nota) as nota, consolidado_cabeza.nom_alumno as alumno')
            ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
            ->where('consolidado.cod_ugel','=',$cod_ugel)
            ->where('consolidado.cod_institucion', '=', $cod_institucion)
            ->where('consolidado_cabeza.nom_seccion', '=', $nom_seccion)
            ->groupBy('consolidado_cabeza.nom_alumno')
            ->get();
            $num = 1;
            $nivel1=0; $nivel2=0; $nivel3=0; $nivel4=0;
            foreach($resultados0 as $resultado0){
                $resultado = new EstadisticaAlumnoModelo();
                $resultado1 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->select('consolidado_cabeza.nom_comentario as nivel')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$cod_ugel)
                ->where('consolidado.cod_institucion', '=', $cod_institucion)
                ->where('consolidado_cabeza.nom_seccion', '=', $nom_seccion)
                ->where('consolidado_cabeza.nom_alumno','=',$resultado0->alumno)                
                ->get();
                $resultado->n=$num;
                $resultado->alumno=$resultado0->alumno;
                $resultado->nota=$resultado0->nota;
                $resultado->nivel=$resultado1[0]->nivel;
                $num++;
                $resultados[] = $resultado;
                switch ($resultado1[0]->nivel) {
                    case "PREVIO AL INICIO":
                        $nivel1++;
                        break;
                    case "INICIO":
                        $nivel2++;
                        break;
                    case "EN PROCESO":
                        $nivel3++;
                        break;
                    default:
                        $nivel4++;
                        break;                    
                }
                $grafico->n1=$nivel1;
                $grafico->n2=$nivel2;
                $grafico->n3=$nivel3;
                $grafico->n4=$nivel4;
            }
        }
        $chartjs = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 400, 'height' => 100])
        ->labels(['Nivel de Progreso'])
        ->datasets([
            [
                "label" => "PREVIO AL INICIO",
                'backgroundColor' => 'red',
                'data' => [$grafico->n1]
            ],
            [
                "label" => "INICIO",
                'backgroundColor' => 'yellow',
                'data' => [$grafico->n2]
            ],
            [
               "label" => "EN PROCESO",
               'backgroundColor' => 'blue',
               'data' => [$grafico->n3]
           ],
           [
               "label" => "LOGRO PREVISTO",
               'backgroundColor' => 'green',
               'data' => [$grafico->n4]
           ]
        ])
        ->options([]);

        return view('aplicacion.reportes.estadistica_alumno',
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'nom_evaluacion_seleccionada' => $nom_evaluacion,
            'ugel_seleccionada' => $cod_ugel, 'nom_ugel_seleccionada' => $nom_ugel,
            'institucion_seleccionada' => $cod_institucion, 'nom_institucion_seleccionada' => $nom_institucion,
            'seccion_seleccionada' => $nom_seccion,            
            'chartjs' => $chartjs]);
    }

    public function estadistica_seccion(Request $request)
    {
        $cod_evaluacion = $request->input('cod_evaluacion');
        $cod_ugel = $request->input('cod_ugel');
        $cod_institucion = $request->input('cod_institucion');
        $nom_evaluacion = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS nom_evaluacion"))
            ->where('cod_evaluacion','=',$cod_evaluacion)->first()->nom_evaluacion;
        $nom_ugel = Ugel::where('cod_ugel','=',$cod_ugel)->first()->nom_ugel;
        $nom_institucion = Institucion::where('cod_institucion','=',$cod_institucion)->first()->nom_institucion;
        $resultados = [];
        $grafico = new EstadisticaSeccionModelo();
        if ($cod_evaluacion>0){
            $resultados0 = DB::table('consolidado')
            ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')            
            ->selectraw('count(*) as nalumnos, consolidado_cabeza.nom_seccion as seccion')
            ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
            ->where('consolidado.cod_ugel','=',$cod_ugel)
            ->where('consolidado.cod_institucion', '=', $cod_institucion)
            ->groupBy('consolidado_cabeza.nom_seccion')
            ->get();
            $num = 1;            
            foreach($resultados0 as $resultado0){
                $resultado = new EstadisticaSeccionModelo();
                $resultado1 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n1')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$cod_ugel)
                ->where('consolidado.cod_institucion', '=', $cod_institucion)
                ->where('consolidado_cabeza.nom_seccion','=',$resultado0->seccion)
                ->where('consolidado_cabeza.nom_comentario','=','PREVIO AL INICIO')
                ->get();
                $resultado2 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n2')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$cod_ugel)
                ->where('consolidado.cod_institucion', '=', $cod_institucion)
                ->where('consolidado_cabeza.nom_seccion','=',$resultado0->seccion)
                ->where('consolidado_cabeza.nom_comentario','=','INICIO')              
                ->get();
                $resultado3 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n3')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$cod_ugel)
                ->where('consolidado.cod_institucion', '=', $cod_institucion)
                ->where('consolidado_cabeza.nom_seccion','=',$resultado0->seccion)
                ->where('consolidado_cabeza.nom_comentario','=','EN PROCESO')
                ->get();
                $resultado4 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n4')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$cod_ugel)
                ->where('consolidado.cod_institucion', '=', $cod_institucion)
                ->where('consolidado_cabeza.nom_seccion','=',$resultado0->seccion)
                ->where('consolidado_cabeza.nom_comentario','=','LOGRO PREVISTO')      
                ->get();
                $resultado->n=$num;
                $resultado->seccion=$resultado0->seccion;
                $resultado->nalumnos=$resultado0->nalumnos;
                $resultado->n1=$resultado1[0]->n1;
                $resultado->n2=$resultado2[0]->n2;
                $resultado->n3=$resultado3[0]->n3;
                $resultado->n4=$resultado4[0]->n4;
                $resultado->p1=round(100*$resultado->n1/$resultado->nalumnos,2);
                $resultado->p2=round(100*$resultado->n2/$resultado->nalumnos,2);
                $resultado->p3=round(100*$resultado->n3/$resultado->nalumnos,2);
                $resultado->p4=round(100*$resultado->n4/$resultado->nalumnos,2);
                $num++;
                $resultados[] = $resultado;
                $grafico->n1=$grafico->n1+$resultado->n1;
                $grafico->n2=$grafico->n2+$resultado->n2;
                $grafico->n3=$grafico->n3+$resultado->n3;
                $grafico->n4=$grafico->n4+$resultado->n4;
            }
        }
        $chartjs = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 400, 'height' => 100])
        ->labels(['Nivel de Progreso'])
        ->datasets([
            [
                "label" => "PREVIO AL INICIO",
                'backgroundColor' => 'red',
                'data' => [$grafico->n1]
            ],
            [
                "label" => "INICIO",
                'backgroundColor' => 'yellow',
                'data' => [$grafico->n2]
            ],
            [
               "label" => "EN PROCESO",
               'backgroundColor' => 'blue',
               'data' => [$grafico->n3]
           ],
           [
               "label" => "LOGRO PREVISTO",
               'backgroundColor' => 'green',
               'data' => [$grafico->n4]
           ]
        ])
        ->options([]);

        return view('aplicacion.reportes.estadistica_seccion',
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'nom_evaluacion_seleccionada' => $nom_evaluacion,
            'ugel_seleccionada' => $cod_ugel, 'nom_ugel_seleccionada' => $nom_ugel,
            'institucion_seleccionada' => $cod_institucion, 'nom_institucion_seleccionada' => $nom_institucion,
            'chartjs' => $chartjs]);
    }

    public function estadistica_detallado(Request $request)
    {
        $cod_evaluacion = $request->input('cod_evaluacion');
        $cod_ugel = $request->input('cod_ugel');
        $nom_evaluacion = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS nom_evaluacion"))
            ->where('cod_evaluacion','=',$cod_evaluacion)->first()->nom_evaluacion;
        $nom_ugel = Ugel::where('cod_ugel','=',$cod_ugel)->first()->nom_ugel;
        $resultados = [];
        $grafico = new EstadisticaDetalladoModelo();
        if ($cod_evaluacion>0){
            $resultados0 = DB::table('consolidado')
            ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')            
            ->selectraw('count(*) as nalumnos, consolidado.nom_institucion as institucion, consolidado.cod_institucion as cod_institucion')
            ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
            ->where('consolidado.cod_ugel','=',$cod_ugel)
            ->groupBy('consolidado.cod_institucion')
            ->groupBy('consolidado.nom_institucion')
            ->get();
            $num = 1;
            foreach($resultados0 as $resultado0){
                $resultado = new EstadisticaDetalladoModelo();
                $resultado1 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n1')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$cod_ugel)
                ->where('consolidado.cod_institucion','=',$resultado0->cod_institucion)
                ->where('consolidado_cabeza.nom_comentario','=','PREVIO AL INICIO')
                ->get();
                $resultado2 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n2')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$cod_ugel)
                ->where('consolidado.cod_institucion','=',$resultado0->cod_institucion)
                ->where('consolidado_cabeza.nom_comentario','=','INICIO')              
                ->get();
                $resultado3 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n3')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$cod_ugel)
                ->where('consolidado.cod_institucion','=',$resultado0->cod_institucion)
                ->where('consolidado_cabeza.nom_comentario','=','EN PROCESO')
                ->get();
                $resultado4 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n4')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$cod_ugel)
                ->where('consolidado.cod_institucion','=',$resultado0->cod_institucion)
                ->where('consolidado_cabeza.nom_comentario','=','LOGRO PREVISTO')      
                ->get();
                $resultado->n=$num;
                $resultado->cod_institucion=$resultado0->cod_institucion;
                $resultado->institucion=$resultado0->institucion;
                $resultado->nalumnos=$resultado0->nalumnos;
                $resultado->n1=$resultado1[0]->n1;
                $resultado->n2=$resultado2[0]->n2;
                $resultado->n3=$resultado3[0]->n3;
                $resultado->n4=$resultado4[0]->n4;
                $resultado->p1=round(100*$resultado->n1/$resultado->nalumnos,2);
                $resultado->p2=round(100*$resultado->n2/$resultado->nalumnos,2);
                $resultado->p3=round(100*$resultado->n3/$resultado->nalumnos,2);
                $resultado->p4=round(100*$resultado->n4/$resultado->nalumnos,2);
                $num++;
                $resultados[] = $resultado;
                $grafico->n1=$grafico->n1+$resultado->n1;
                $grafico->n2=$grafico->n2+$resultado->n2;
                $grafico->n3=$grafico->n3+$resultado->n3;
                $grafico->n4=$grafico->n4+$resultado->n4;
            }
        }
        $chartjs = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 400, 'height' => 100])
        ->labels(['Nivel de Progreso'])
        ->datasets([
            [
                "label" => "PREVIO AL INICIO",
                'backgroundColor' => 'red',
                'data' => [$grafico->n1]
            ],
            [
                "label" => "INICIO",
                'backgroundColor' => 'yellow',
                'data' => [$grafico->n2]
            ],
            [
               "label" => "EN PROCESO",
               'backgroundColor' => 'blue',
               'data' => [$grafico->n3]
           ],
           [
               "label" => "LOGRO PREVISTO",
               'backgroundColor' => 'green',
               'data' => [$grafico->n4]
           ]
        ])
        ->options([]);

        return view('aplicacion.reportes.estadistica_detallado',
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'nom_evaluacion_seleccionada' => $nom_evaluacion,
            'ugel_seleccionada' => $cod_ugel, 'nom_ugel_seleccionada' => $nom_ugel,
            'chartjs' => $chartjs]);
    }

    public function resumen_preguntas(Request $request)
    {
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
            ->where('ind_procesado','=',1)
            ->pluck('descripcion', 'cod_evaluacion');
        $cod_evaluacion = $request->input('cod_evaluacion');
        $resultados = [];
        $grafico = new ResumenPreguntasModelo();
        if($cod_evaluacion>0){            
            for($i=0; $i<=4; $i++) {
                $resultado1 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p1')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',1)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado2 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p2')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',2)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado3 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p3')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',3)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado4 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p4')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',4)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado5 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p5')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',5)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado6 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p6')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',6)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado7 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p7')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',7)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado8 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p8')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',8)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado9 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p9')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',9)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado10 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p10')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',10)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado11 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p11')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',11)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado12 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p12')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',12)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado13 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p13')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',13)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado14 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p14')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',14)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado15 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p15')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',15)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado16 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p16')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',16)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado17 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p17')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',17)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado18 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p18')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',18)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado19 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p19')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',19)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado20 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p20')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',20)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado21 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p21')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',21)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado22 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p22')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',22)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado23 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p23')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',23)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado24 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p24')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',24)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado25 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p25')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',25)
                ->where('consolidado_cuerpo.num_respuesta','=',$i)
                ->get();
                $resultado = new ResumenPreguntasModelo();
                $resultado->pregunta=$i;
                $resultado->p1=$resultado1[0]->p1;
                $resultado->p2=$resultado2[0]->p2;
                $resultado->p3=$resultado3[0]->p3;
                $resultado->p4=$resultado4[0]->p4;
                $resultado->p5=$resultado5[0]->p5;
                $resultado->p6=$resultado6[0]->p6;
                $resultado->p7=$resultado7[0]->p7;
                $resultado->p8=$resultado8[0]->p8;
                $resultado->p9=$resultado9[0]->p9;
                $resultado->p10=$resultado10[0]->p10;
                $resultado->p11=$resultado11[0]->p11;
                $resultado->p12=$resultado12[0]->p12;
                $resultado->p13=$resultado13[0]->p13;
                $resultado->p14=$resultado14[0]->p14;
                $resultado->p15=$resultado15[0]->p15;
                $resultado->p16=$resultado16[0]->p16;
                $resultado->p17=$resultado17[0]->p17;
                $resultado->p18=$resultado18[0]->p18;
                $resultado->p19=$resultado19[0]->p19;
                $resultado->p20=$resultado20[0]->p20;
                $resultado->p21=$resultado21[0]->p21;
                $resultado->p22=$resultado22[0]->p22;
                $resultado->p23=$resultado23[0]->p23;
                $resultado->p24=$resultado24[0]->p24;
                $resultado->p25=$resultado25[0]->p25;
                $resultados[]=$resultado;
                $grafico->p1=$grafico->p1+$resultado->p1;
                $grafico->p2=$grafico->p2+$resultado->p2;
                $grafico->p3=$grafico->p3+$resultado->p3;
                $grafico->p4=$grafico->p4+$resultado->p4;
                $grafico->p5=$grafico->p5+$resultado->p5;
                $grafico->p6=$grafico->p6+$resultado->p6;
                $grafico->p7=$grafico->p7+$resultado->p7;
                $grafico->p8=$grafico->p8+$resultado->p8;
                $grafico->p9=$grafico->p9+$resultado->p9;
                $grafico->p10=$grafico->p10+$resultado->p10;
                $grafico->p11=$grafico->p11+$resultado->p11;
                $grafico->p12=$grafico->p12+$resultado->p12;
                $grafico->p13=$grafico->p13+$resultado->p13;
                $grafico->p14=$grafico->p14+$resultado->p14;
                $grafico->p15=$grafico->p15+$resultado->p15;
                $grafico->p16=$grafico->p16+$resultado->p16;
                $grafico->p17=$grafico->p17+$resultado->p17;
                $grafico->p18=$grafico->p18+$resultado->p18;
                $grafico->p19=$grafico->p19+$resultado->p19;
                $grafico->p20=$grafico->p20+$resultado->p20;
                $grafico->p21=$grafico->p21+$resultado->p21;
                $grafico->p22=$grafico->p22+$resultado->p22;
                $grafico->p23=$grafico->p23+$resultado->p23;
                $grafico->p24=$grafico->p24+$resultado->p24;
                $grafico->p25=$grafico->p25+$resultado->p25;
            }            
        }
        $chartjs = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 400, 'height' => 100])
        ->labels(['Nivel de Progreso'])
        ->datasets([
            [
                "label" => "1",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p1]
            ],
            [
                "label" => "2",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p2]
            ],
            [
               "label" => "3",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p3]
            ],
            [
               "label" => "4",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p4]
            ],
            [
                "label" => "5",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p5]
            ],
            [
                "label" => "6",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p6]
            ],
            [
               "label" => "7",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p7]
            ],
            [
               "label" => "8",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p8]
            ],
            [
                "label" => "9",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p9]
            ],
            [
                "label" => "10",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p10]
            ],
            [
               "label" => "11",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p11]
            ],
            [
               "label" => "12",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p12]
            ],
            [
                "label" => "13",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p13]
            ],
            [
                "label" => "14",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p14]
            ],
            [
               "label" => "15",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p15]
            ],
            [
               "label" => "16",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p16]
            ],
            [
                "label" => "17",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p17]
            ],
            [
                "label" => "18",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p18]
            ],
            [
               "label" => "19",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p19]
            ],
            [
               "label" => "20",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p20]
            ],
            [
                "label" => "21",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p21]
            ],
            [
                "label" => "22",
                'backgroundColor' => 'blue',
                'data' => [$grafico->p22]
            ],
            [
               "label" => "23",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p23]
            ],
            [
               "label" => "24",
               'backgroundColor' => 'blue',
               'data' => [$grafico->p24]
            ],
            [
                "label" => "25",
                'backgroundColor' => 'red',
                'data' => [$grafico->p25]
            ],
        ])
        ->options([]);
        return view('aplicacion.reportes.resumen_preguntas', 
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'evaluaciones' => $evaluaciones,
            'chartjs' => $chartjs]);
    }
    
    public function estadistica_resumen(Request $request)
    {
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
            ->where('ind_procesado','=',1)
            ->pluck('descripcion', 'cod_evaluacion');
        $cod_evaluacion = $request->input('cod_evaluacion');
        $resultados = [];
        $grafico = new EstadisticaResumenModelo();
        if ($cod_evaluacion>0){
            $resultados0 = DB::table('consolidado')
            ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
            ->select('consolidado.nom_ugel as ugel')
            ->selectraw('count(*) as nalumnos, consolidado.cod_ugel as cod_ugel')
            ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
            ->groupBy('consolidado.cod_ugel')
            ->groupBy('consolidado.nom_ugel')
            ->get();
            $num = 1;
            foreach($resultados0 as $resultado0){
                $resultado = new EstadisticaResumenModelo();
                $resultado1 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n1')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$resultado0->cod_ugel)
                ->where('consolidado_cabeza.nom_comentario','=','PREVIO AL INICIO')            
                ->get();
                $resultado2 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n2')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$resultado0->cod_ugel)
                ->where('consolidado_cabeza.nom_comentario','=','INICIO')            
                ->get();
                $resultado3 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n3')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$resultado0->cod_ugel)
                ->where('consolidado_cabeza.nom_comentario','=','EN PROCESO')             
                ->get();
                $resultado4 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n4')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.cod_ugel','=',$resultado0->cod_ugel)
                ->where('consolidado_cabeza.nom_comentario','=','LOGRO PREVISTO')              
                ->get();
                $resultado->n=$num;
                $resultado->cod_ugel=$resultado0->cod_ugel;
                $resultado->ugel=$resultado0->ugel;
                $resultado->nalumnos=$resultado0->nalumnos;
                $resultado->n1=$resultado1[0]->n1;
                $resultado->n2=$resultado2[0]->n2;
                $resultado->n3=$resultado3[0]->n3;
                $resultado->n4=$resultado4[0]->n4;
                $resultado->p1=round(100*$resultado->n1/$resultado->nalumnos,2);
                $resultado->p2=round(100*$resultado->n2/$resultado->nalumnos,2);
                $resultado->p3=round(100*$resultado->n3/$resultado->nalumnos,2);
                $resultado->p4=round(100*$resultado->n4/$resultado->nalumnos,2);
                $num++;
                $resultados[] = $resultado;
                $grafico->n1=$grafico->n1+$resultado->n1;
                $grafico->n2=$grafico->n2+$resultado->n2;
                $grafico->n3=$grafico->n3+$resultado->n3;
                $grafico->n4=$grafico->n4+$resultado->n4;
            }
        }
        $chartjs = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 400, 'height' => 100])
        ->labels(['Nivel de Progreso'])
        ->datasets([
            [
                "label" => "PREVIO AL INICIO",
                'backgroundColor' => 'red',
                'data' => [$grafico->n1]
            ],
            [
                "label" => "INICIO",
                'backgroundColor' => 'yellow',
                'data' => [$grafico->n2]
            ],
            [
               "label" => "EN PROCESO",
               'backgroundColor' => 'blue',
               'data' => [$grafico->n3]
           ],
           [
               "label" => "LOGRO PREVISTO",
               'backgroundColor' => 'green',
               'data' => [$grafico->n4]
           ]
        ])
        ->options([]);
        return view('aplicacion.reportes.estadistica_resumen', 
            ['resultados' => $resultados, 'evaluacion_seleccionada' => $cod_evaluacion, 'evaluaciones' => $evaluaciones,
            'chartjs' => $chartjs]);
    }
}