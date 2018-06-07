<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Prueba;
use App\PruebaDetalle;
use App\Evaluacion;
use App\EvaluacionDetalle;
use App\Consolidado;
use App\ConsolidadoCabeza;
use App\ConsolidadoCuerpo;

use App\Institucion;
use App\Ugel;
use App\Puntaje;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Validator;
use App\Exceptions\NotFoundmonExceptio;
use Exception;

class CargarEvaluacionModelo {
    public $cod_evaluacion;
}

class CargarEvaluacionController extends Controller
{

    public function index() {
        $modelo = new CargarEvaluacionModelo();
        $modelo->cod_evaluacion = 0;

        // $evaluaciones = Evaluacion::select(
        //     DB::raw("CONCAT(NUM_ANIO,' ',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
        //     ->where('ind_procesado', '0')
        //     ->pluck('descripcion', 'cod_evaluacion');

        $resultado = Evaluacion::where('ind_procesado', '0')->get();
        $evaluaciones = [];

        foreach($resultado as $evaluacion) {

            $nom_tipo = "?";            
            if ($evaluacion->num_tipo == 1) {
                $nom_tipo = "MATEMATICA";
            } elseif ($evaluacion->num_tipo == 2) {
                $nom_tipo = "COMUNICACION";
            } elseif ($evaluacion->num_tipo == 3) {
                $nom_tipo = "CTA";
            }

            $evaluaciones[$evaluacion->cod_evaluacion] = "$evaluacion->num_anio - $evaluacion->num_correlativo - $nom_tipo";

        }

        return view('aplicacion.cargar_evaluacion.index', [
            'modelo' => $modelo, 
            'evaluaciones' => $evaluaciones
        ]);
    }

    public function guardar(Request $solicitud) {

        $errores = [];
        try {

            $messages = [
                'cod_evaluacion.required' => 'Seleccione una evaluación',
                'archivo.required' => 'Seleccione un archivo para cargar',
            ];
            
            $validator = Validator::make($solicitud->all(), [
                'cod_evaluacion' => 'required',
                'archivo' => 'required',
            ], $messages);
    
            if ($validator->fails()) {
                return redirect('/cargar_evaluacion')
                            ->withErrors($validator)
                            ->withInput();
            }
    
            $cod_evaluacion = $solicitud->input('cod_evaluacion');
            $archivo = $solicitud->file('archivo');
    
            $evaluacion = Evaluacion::with('detalle')->where('cod_evaluacion', $cod_evaluacion)->first();
            $puntajes = Puntaje::all();
            $instituciones = Institucion::with('ugel')->get();
    
            $num_peso_total = $evaluacion->detalle->sum('num_peso');
            $num_preguntas = $evaluacion->detalle->count();
            DB::beginTransaction();
            
            $evaluacion->ind_procesado = 1;
            $evaluacion->save();
    
            if ($solicitud->hasFile('archivo')) {
    
                $data = file($archivo->getRealPath());
                $indice = 0;
    
                $nom_alumno = [];
                $num_seccion = [];
                $num_institucion = [];

                foreach($data as $linea) {                    
                    $array_linea = explode(';',$linea);
                    $indice++;
                    $ind_discapacitado_valor = $array_linea[6];
                    $nom_alumno_valor = $array_linea[7];// trim(substr($linea, 40, 14))." ".trim(substr($linea, 54, 12));
                    $num_seccion_valor = $array_linea[20] . $array_linea[21];// substr($linea, 90, 2);
                    $num_institucion_valor = $array_linea[16];// substr($linea, 74, 7);
                    
                    for($a=22; $a<22+$num_preguntas; $a++)
                    {                        
                        if ($array_linea[$a] == "*")
                        {
                            $errores[] = "En la linea $indice, no se encontro una respuesta válida.";
                            $nota[$indice-1][$a-22] = "0";
                        }
                        else
                        {
                            if ($array_linea[$a] == "")
                            {
                                $nota[$indice-1][$a-22] = "0";
                            }
                            else 
                            {
                                $nota[$indice-1][$a-22] = $array_linea[$a];
                            }
                        }                                                
                    }

                    if(
                        $ind_discapacitado_valor == "*" ||
                        $ind_discapacitado_valor == ""
                    )
                    {
                        $errores[] = "En la linea $indice, no se encontro el indicador de discapacitado.";
                    }

                    if(
                        $num_seccion_valor == "**" ||
                        $num_seccion_valor == ""
                    )
                    {
                        $errores[] = "En la linea $indice, no se encontro una sección válida.";
                    }
                
                    $institucion = $instituciones->where('num_institucion', $num_institucion_valor)->first();
                    if($institucion == null)
                    {
                        $errores[] = "En la linea $indice, no se encontro la institución con código $num_institucion_valor.";
                    }
                    
                    $ind_discapacitado[] = $ind_discapacitado_valor;
                    $nom_alumno[] = $nom_alumno_valor;
                    $num_seccion[] = (int)$num_seccion_valor;
                    $num_institucion[] = $num_institucion_valor;
                    //$nota[$indice] = str_split($nota_valor);
                }
                if(count($errores))
                {
                    throw new Exception("Se encontraron advertencias en el archivo");
                }

                for($i=0; $i<$indice; $i++)
                {
                    
                    $institucion = $instituciones->where('num_institucion', $num_institucion[$i])->first();

                    $prueba = new Prueba();
                    $prueba->cod_institucion = $institucion->cod_institucion;
                    $prueba->nom_alumno = $nom_alumno[$i];
                    $prueba->num_seccion = (int)$num_seccion[$i];
                    $prueba->ind_discapacitado = $ind_discapacitado[$i];
                    $prueba->save();
                    
                    $consolidado = new Consolidado();
                    $consolidado->cod_evaluacion = $cod_evaluacion;
                    $consolidado->nom_ugel = $institucion->ugel->nom_ugel;
                    $consolidado->nom_institucion = $institucion->nom_institucion;
                    $consolidado->num_peso_total = $num_peso_total;   //suma detalle evaluacion num_peso
                    $consolidado->cod_institucion = $institucion->cod_institucion;
                    $consolidado->cod_ugel = $institucion->cod_ugel;
                    $prueba->consolidado()->save($consolidado);
                    
                    $secciones = "ABCDEFGHIJKLM";
                    $notas = "ABCDEFGHIJKLM";
                    $num_nota = 0;
                                        
                    for($j=0; $j<$num_preguntas; $j++)
                    {
                        $evaluacionDetalle = $evaluacion->detalle->where('num_pregunta', $j + 1)->first();                        
                        $num_respuesta_marcada = $nota[$i][$j];
                        if($evaluacionDetalle->num_respuesta == $num_respuesta_marcada)
                        {
                            $num_nota = (int)$num_nota + (int)$evaluacionDetalle->num_peso;
                        }                     
                    }
                    $num_puntaje = 0;
                    if($num_nota > 0){
                        $num_puntaje = ($num_nota * 100) / $num_peso_total;
                    }
                    
                    $nom_comentario = $puntajes
                        ->where('num_minimo', '<', $num_puntaje)
                        ->where('num_maximo', '>=', $num_puntaje)
                        ->first()
                        ->nom_comentario;
                    $consolidadoCabeza = new ConsolidadoCabeza();
                    $consolidadoCabeza->ind_discapacitado = $ind_discapacitado[$i];
                    $consolidadoCabeza->nom_alumno = $nom_alumno[$i];
                    $consolidadoCabeza->num_nota = $num_nota;   //n pregunta , sumar las respuestas acertadas con el detalle de evaluacion <- si coinciden usar el peso del detalle de evaluacion
                    $consolidadoCabeza->nom_comentario = $nom_comentario; //de puntaje, calcular entre num_peso_total con num_nota (regla de 3)
                    
                    $seccion = $secciones[(int)$num_seccion[$i] - 1];
                    $consolidadoCabeza->nom_seccion = $seccion;
                  
                    $consolidado->cabeza()->save($consolidadoCabeza);
    
                    for($j=0; $j<$num_preguntas; $j++) {
    
                        $pruebaDetalle = new PruebaDetalle();
    
                        $evalucionDetalle = $evaluacion->detalle->where('num_pregunta', $j + 1)->first();
                        $pruebaDetalle->cod_evaluacion_detalle = $evalucionDetalle->cod_evaluacion_detalle;
                        $pruebaDetalle->num_respuesta = (int)$nota[$i][$j];
                        $prueba->detalle()->save($pruebaDetalle);
    
                        $consolidadoCuerpo = new ConsolidadoCuerpo();
                        $consolidadoCuerpo->num_pregunta = $j + 1;

                        $val_nota = (int)$nota[$i][$j];
                        $nom_respuesta = "EN BLANCO";
                        if($val_nota > 0) 
                        {
                            $nom_respuesta = $notas[$val_nota - 1];
                        }

                        $nom_comentario = "CORRECTO";
                        if($val_nota != $evalucionDetalle->num_respuesta)
                        {
                            $nom_comentario = $evalucionDetalle->nom_mensaje;
                        }
                        $consolidadoCuerpo->nom_respuesta = $nom_respuesta;
                        $consolidadoCuerpo->nom_comentario = $nom_comentario;
                        $consolidadoCabeza->cuerpo()->save($consolidadoCuerpo);
                    }
                }
            }
            
        }
        catch(Exception $e)
        {
            //die($e);
            //dump("triste");
            DB::rollBack();
            return redirect('/cargar_evaluacion')->withErrors(["errores" => $errores])->withInput();
        }

        DB::commit();

        return redirect('/cargar_evaluacion')->with('exito', 'La carga de evaluación se realizo correctamente');
    }
}