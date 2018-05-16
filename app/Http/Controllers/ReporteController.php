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
use App\Http\Controllers\Redirect;

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

class AlumnosDiscapacitados {
    public $n;
    public $ugel;
    public $institucion;
    public $seccion;
    public $alumno;
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
        ->where('ind_procesado','=',0)
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
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO,'-',(CASE WHEN num_tipo = 1 THEN 'MATEMATICA' WHEN num_tipo = 2 THEN 'COMUNICACION' ELSE 'CTA' END)) AS nom_evaluacion"))
            ->where('cod_evaluacion','=',$cod_evaluacion)->first()->nom_evaluacion;
        $nom_ugel = Ugel::where('cod_ugel','=',$cod_ugel)->first()->nom_ugel;
        $nom_institucion = Institucion::where('cod_institucion','=',$cod_institucion)->first()->nom_institucion;        
        $grafico = new EstadisticaAlumnoModelo();
        if ($cod_evaluacion>0){
            $resultados = DB::table('consolidado')
            ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
            ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
            ->select('consolidado_cuerpo.num_pregunta as pregunta','consolidado_cuerpo.nom_respuesta as respuesta','consolidado_cuerpo.nom_comentario as comentario')
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
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO,'-',(CASE WHEN num_tipo = 1 THEN 'MATEMATICA' WHEN num_tipo = 2 THEN 'COMUNICACION' ELSE 'CTA' END)) AS nom_evaluacion"))
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
        $tipo_usuario = $request->session()->get('num_tipo_usuario');
        switch ($tipo_usuario) {
            case 1:
            case 2:                
            case 3:
                $cod_ugel = $request->input('cod_ugel');
                $cod_institucion = $request->input('cod_institucion');
                $ind_form = 0;
                break;
            case 4:
                $cod_ugel = $request->session()->get('cod_ugel');
                $cod_institucion = $request->session()->get('cod_institucion');
                $ind_form = 1;
                break;                    
        }
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO,'-',(CASE WHEN num_tipo = 1 THEN 'MATEMATICA' WHEN num_tipo = 2 THEN 'COMUNICACION' ELSE 'CTA' END)) AS descripcion"),'cod_evaluacion')
            ->where('ind_procesado','=',1)
            ->pluck('descripcion', 'cod_evaluacion');
        $cod_evaluacion = $request->input('cod_evaluacion');
        //$cod_ugel = $request->input('cod_ugel');
        //$cod_institucion = $request->input('cod_institucion');
        $nom_evaluacion = "";
        $nom_ugel = Ugel::where('cod_ugel','=',$cod_ugel)->first()->nom_ugel;
        $nom_institucion = Institucion::where('cod_institucion','=',$cod_institucion)->first()->nom_institucion;
        $resultados = [];
        $grafico = new EstadisticaSeccionModelo();
        if ($cod_evaluacion>0){
            $nom_evaluacion = Evaluacion::select(
                DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO,'-',(CASE WHEN num_tipo = 1 THEN 'MATEMATICA' WHEN num_tipo = 2 THEN 'COMUNICACION' ELSE 'CTA' END)) AS nom_evaluacion"))
                ->where('cod_evaluacion','=',$cod_evaluacion)->first()->nom_evaluacion;
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
            'evaluaciones' => $evaluaciones,
            'ind_form' => $ind_form, 'chartjs' => $chartjs]);
    }

    public function estadistica_detallado(Request $request)
    {        
        $tipo_usuario = $request->session()->get('num_tipo_usuario');
        switch ($tipo_usuario) {
            case 1:
            case 2:
                $cod_ugel = $request->input('cod_ugel');
                $ind_form = 0;
                break;
            case 3:
                $cod_ugel = $request->session()->get('cod_ugel');
                $ind_form = 1;
                break;
            case 4:
                return $this->estadistica_seccion($request);
                break;                    
        }
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO,'-',(CASE WHEN num_tipo = 1 THEN 'MATEMATICA' WHEN num_tipo = 2 THEN 'COMUNICACION' ELSE 'CTA' END)) AS descripcion"),'cod_evaluacion')
            ->where('ind_procesado','=',1)
            ->pluck('descripcion', 'cod_evaluacion');
        $cod_evaluacion = $request->input('cod_evaluacion');
        //$cod_ugel = $request->input('cod_ugel');
        $nom_evaluacion = "";
        $nom_ugel = Ugel::where('cod_ugel','=',$cod_ugel)->first()->nom_ugel;
        $resultados = [];
        $grafico = new EstadisticaDetalladoModelo();
        if ($cod_evaluacion>0)
        {
            $nom_evaluacion = Evaluacion::select(
                DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO,'-',(CASE WHEN num_tipo = 1 THEN 'MATEMATICA' WHEN num_tipo = 2 THEN 'COMUNICACION' ELSE 'CTA' END)) AS nom_evaluacion"))
                ->where('cod_evaluacion','=',$cod_evaluacion)->first()->nom_evaluacion;            
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
            'evaluaciones' => $evaluaciones,
            'ind_form' => $ind_form, 'chartjs' => $chartjs]);
    }

    public function resumen_preguntas(Request $request)
    {        
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO,'-',(CASE WHEN num_tipo = 1 THEN 'MATEMATICA' WHEN num_tipo = 2 THEN 'COMUNICACION' ELSE 'CTA' END)) AS descripcion"),'cod_evaluacion')
            ->where('ind_procesado','=',1)
            ->pluck('descripcion', 'cod_evaluacion');
        $cod_evaluacion = $request->input('cod_evaluacion');
        $resultados = [];
        $grafico = new ResumenPreguntasModelo();
        if($cod_evaluacion>0){
            $entidad = Evaluacion::where('cod_evaluacion', $cod_evaluacion)->first();            
            if(!$entidad)
            {
                die("no existe");
            }
            $num = 1;
            foreach($entidad->detalle()->get() as $detalle)
            {
                $resultado1 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p1')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',$num)
                ->where('consolidado_cuerpo.nom_respuesta','=',"EN BLANCO")
                ->get();
                $resultado2 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p2')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',$num)
                ->where('consolidado_cuerpo.nom_respuesta','=',"A")
                ->get();
                $resultado3 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p3')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',$num)
                ->where('consolidado_cuerpo.nom_respuesta','=',"B")
                ->get();
                $resultado4 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p4')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',$num)
                ->where('consolidado_cuerpo.nom_respuesta','=',"C")
                ->get();
                $resultado5 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->join('consolidado_cuerpo', 'consolidado_cabeza.cod_consolidado_cabeza', '=', 'consolidado_cuerpo.cod_consolidado_cabeza')
                ->selectraw('count(*) as p5')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado_cuerpo.num_pregunta','=',$num)
                ->where('consolidado_cuerpo.nom_respuesta','=',"D")
                ->get();                
                $resultado = new ResumenPreguntasModelo();
                $resultado->pregunta=$num;
                $resultado->p1=$resultado1[0]->p1;
                $resultado->p2=$resultado2[0]->p2;
                $resultado->p3=$resultado3[0]->p3;
                $resultado->p4=$resultado4[0]->p4;
                $resultado->p5=$resultado5[0]->p5;
                $resultados[]=$resultado;
                $grafico->p1=$grafico->p1+$resultado->p1;
                $grafico->p2=$grafico->p2+$resultado->p2;
                $grafico->p3=$grafico->p3+$resultado->p3;
                $grafico->p4=$grafico->p4+$resultado->p4;
                $grafico->p5=$grafico->p5+$resultado->p5;
                $num++;
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
        ])
        ->options([]);
        return view('aplicacion.reportes.resumen_preguntas', 
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'evaluaciones' => $evaluaciones,
            'chartjs' => $chartjs]);
    }
    
    public function estadistica_resumen(Request $request)
    {
        $tipo_usuario = $request->session()->get('num_tipo_usuario');
        if ($tipo_usuario == 1 || $tipo_usuario == 2)
        {
            $evaluaciones = Evaluacion::select(
                DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO,'-',(CASE WHEN num_tipo = 1 THEN 'MATEMATICA' WHEN num_tipo = 2 THEN 'COMUNICACION' ELSE 'CTA' END)) AS descripcion"),'cod_evaluacion')
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
        else 
        {
            return $this->estadistica_detallado($request);
        }
    }

    public function alumnos_discapacitados(Request $request)
    {        
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO,'-',(CASE WHEN num_tipo = 1 THEN 'MATEMATICA' WHEN num_tipo = 2 THEN 'COMUNICACION' ELSE 'CTA' END)) AS descripcion"),'cod_evaluacion')
            ->where('ind_procesado','=',1)
            ->pluck('descripcion', 'cod_evaluacion');
        $cod_evaluacion = $request->input('cod_evaluacion');
        $resultados = [];
        if($cod_evaluacion>0){
            $entidad = Evaluacion::where('cod_evaluacion', $cod_evaluacion)->first();            
            if(!$entidad)
            {
                die("no existe");
            }
            $num = 1;
            $resultadototal = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')                
                ->select('consolidado.nom_ugel as ugel')
                ->select('consolidado.nom_institucion as institucion')
                ->select('consolidado_cabeza.nom_seccion as seccion')
                ->select('consolidado_cabeza.nom_alumno as alumno')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->get();
            foreach($resultadototal as $resultado1)
            {   
                $resultado = new AlumnosDiscapacitados();
                $resultado->n=$num;
                $resultado->ugel=$resultado1->ugel;
                $resultado->institucion=$resultado1->institucion;
                $resultado->seccion=$resultado1->seccion;
                $resultado->alumno=$resultado1->alumno;                
                $resultados[]=$resultado;
                $num++;         
            }            
        }        
        return view('aplicacion.reportes.alumnos_discapacitados', 
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'evaluaciones' => $evaluaciones]);
    }

    public function estadistica_reporte(Request $request)
    {
        $request->replace(['cod_evaluacion' => 0]);
        return $this->estadistica_resumen($request);
    }

    public function resumen_pregunta(Request $request)
    {
        $request->replace(['cod_evaluacion' => 0]);
        return $this->resumen_preguntas($request);
    }

    public function alumno_discapacitado(Request $request)
    {
        $request->replace(['cod_evaluacion' => 0]);
        return $this->alumnos_discapacitados($request);
    }
}