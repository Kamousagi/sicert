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
        $evaluaciones = Evaluacion::where('ind_procesado','=',0)->get();
        return view('aplicacion.reportes.cronograma_evaluacion', ['evaluaciones' => $evaluaciones]);
    }
    public function estadistica_detallado(Request $request)
    {
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
            ->where('ind_procesado','=',1)
            ->pluck('descripcion', 'cod_evaluacion');
        $ugeles = Ugel::select(
            'nom_ugel','nom_ugel')
            ->pluck('nom_ugel', 'nom_ugel');
        $cod_evaluacion = $request->input('cod_evaluacion');
        $cod_ugel = $request->input('cod_ugel');
        $resultados = [];
        if ($cod_evaluacion>0){
            $resultados0 = DB::table('consolidado')
            ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')            
            ->selectraw('count(*) as nalumnos, consolidado.nom_institucion as institucion')
            ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
            ->where('consolidado.nom_ugel','=',$cod_ugel)            
            ->groupBy('consolidado.nom_institucion')
            ->get();
            $num = 1;
            foreach($resultados0 as $resultado0){
                $resultado = new EstadisticaResumenModelo();
                $resultado1 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n1')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.nom_ugel','=',$cod_ugel)
                ->where('consolidado.nom_institucion','=',$resultado0->institucion)
                ->where('consolidado_cabeza.nom_comentario','=','PREVIO AL INICIO')
                ->get();
                $resultado2 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n2')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.nom_ugel','=',$cod_ugel)
                ->where('consolidado.nom_institucion','=',$resultado0->institucion)
                ->where('consolidado_cabeza.nom_comentario','=','INICIO')              
                ->get();
                $resultado3 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n3')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.nom_ugel','=',$cod_ugel)
                ->where('consolidado.nom_institucion','=',$resultado0->institucion)
                ->where('consolidado_cabeza.nom_comentario','=','EN PROCESO')
                ->get();
                $resultado4 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n4')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.nom_ugel','=',$cod_ugel)
                ->where('consolidado.nom_institucion','=',$resultado0->institucion)
                ->where('consolidado_cabeza.nom_comentario','=','LOGRO PREVISTO')      
                ->get();
                $resultado->n=$num;
                $resultado->institucion=$resultado0->institucion;
                $resultado->nalumnos=$resultado0->nalumnos;
                $resultado->n1=$resultado1;
                $resultado->n2=$resultado2;
                $resultado->n3=$resultado3;
                $resultado->n4=$resultado4;
                $resultado->p1=round(100*$resultado1[0]->n1/$resultado0->nalumnos,2);
                $resultado->p2=round(100*$resultado2[0]->n2/$resultado0->nalumnos,2);
                $resultado->p3=round(100*$resultado3[0]->n3/$resultado0->nalumnos,2);
                $resultado->p4=round(100*$resultado4[0]->n4/$resultado->nalumnos,2);
                $num++;
                $resultados[] = $resultado;
            }
        }

        return view('aplicacion.reportes.estadistica_detallado', 
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'ugel_seleccionada' => $cod_ugel,
            'evaluaciones' => $evaluaciones, 'ugeles' => $ugeles]);
    }
    public function estadistica_preguntas(Request $request)
    {
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
            ->where('ind_procesado','=',1)
            ->pluck('descripcion', 'cod_evaluacion');
        $cod_evaluacion = $request->input('cod_evaluacion');
        $resultados = [];
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
                $resultado = new EstadisticaDetalladoModelo();
                $resultado->pregunta=$i;
                $resultado->p1=$resultado1;
                $resultado->p2=$resultado2;
                $resultado->p3=$resultado3;
                $resultado->p4=$resultado4;
                $resultado->p5=$resultado5;
                $resultado->p6=$resultado6;
                $resultado->p7=$resultado7;
                $resultado->p8=$resultado8;
                $resultado->p9=$resultado9;
                $resultado->p10=$resultado10;
                $resultado->p11=$resultado11;
                $resultado->p12=$resultado12;
                $resultado->p13=$resultado13;
                $resultado->p14=$resultado14;
                $resultado->p15=$resultado15;
                $resultado->p16=$resultado16;
                $resultado->p17=$resultado17;
                $resultado->p18=$resultado18;
                $resultado->p19=$resultado19;
                $resultado->p20=$resultado20;
                $resultado->p21=$resultado21;
                $resultado->p22=$resultado22;
                $resultado->p23=$resultado23;
                $resultado->p24=$resultado24;
                $resultado->p25=$resultado25;
                $resultados[]=$resultado;
            }            
        }        
        return view('aplicacion.reportes.estadistica_preguntas', 
            ['resultados' => $resultados,'evaluacion_seleccionada' => $cod_evaluacion, 'evaluaciones' => $evaluaciones]);
    }
    
    public function estadistica_resumen(Request $request)
    {
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,'-',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
            ->where('ind_procesado','=',1)
            ->pluck('descripcion', 'cod_evaluacion');
        $cod_evaluacion = $request->input('cod_evaluacion');
        $resultados = [];
        if ($cod_evaluacion>0){
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
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.nom_ugel','=',$resultado0->ugel)
                ->where('consolidado_cabeza.nom_comentario','=','PREVIO AL INICIO')            
                ->get();
                $resultado2 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n2')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.nom_ugel','=',$resultado0->ugel)
                ->where('consolidado_cabeza.nom_comentario','=','INICIO')            
                ->get();
                $resultado3 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n3')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.nom_ugel','=',$resultado0->ugel)
                ->where('consolidado_cabeza.nom_comentario','=','EN PROCESO')             
                ->get();
                $resultado4 = DB::table('consolidado')
                ->join('consolidado_cabeza', 'consolidado.cod_consolidado', '=', 'consolidado_cabeza.cod_consolidado')
                ->selectraw('count(*) as n4')
                ->where('consolidado.cod_evaluacion','=',$cod_evaluacion)
                ->where('consolidado.nom_ugel','=',$resultado0->ugel)
                ->where('consolidado_cabeza.nom_comentario','=','LOGRO PREVISTO')              
                ->get();
                $resultado->n=$num;
                $resultado->ugel=$resultado0->ugel;
                $resultado->nalumnos=$resultado0->nalumnos;
                $resultado->n1=$resultado1;
                $resultado->n2=$resultado2;
                $resultado->n3=$resultado3;
                $resultado->n4=$resultado4;
                $resultado->p1=round(100*$resultado1[0]->n1/$resultado0->nalumnos,2);
                $resultado->p2=round(100*$resultado2[0]->n2/$resultado0->nalumnos,2);
                $resultado->p3=round(100*$resultado3[0]->n3/$resultado0->nalumnos,2);
                $resultado->p4=round(100*$resultado4[0]->n4/$resultado->nalumnos,2);
                $num++;               
                $resultados[] = $resultado;
            }
        }
        return view('aplicacion.reportes.estadistica_resumen', 
            ['resultados' => $resultados, 'evaluacion_seleccionada' => $cod_evaluacion, 'evaluaciones' => $evaluaciones]);
    }
}