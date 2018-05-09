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

class CargarEvaluacionModelo {
    public $cod_evaluacion;
}

class CargarEvaluacionController extends Controller
{

    public function index() {
        $modelo = new CargarEvaluacionModelo();
        $modelo->cod_evaluacion = 0;

        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,' ',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
            ->where('ind_procesado', '0')
            ->pluck('descripcion', 'cod_evaluacion');

        return view('aplicacion.cargar_evaluacion.index', ['modelo' => $modelo, 'evaluaciones' => $evaluaciones]);
    }

    public function guardar(Request $solicitud) {

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
        DB::beginTransaction();

        $evaluacion->ind_procesado = 1;
        $evaluacion->save();

        if ($solicitud->hasFile('archivo')) {

            $data = file($archivo->getRealPath());
            $indice = 0;

            foreach($data as $linea) {

                $indice++;
                $nom_alumno = trim(substr($linea, 40, 14))." ".trim(substr($linea, 54, 12));
                $num_seccion = substr($linea, 90, 2);
                $num_institucion = substr($linea, 74, 7);
                $nota = substr($linea, 92, 25);

                if($nota == "*")
                {
                    DB::rollBack();
                    die("no se encontro nota");
                }

                if($num_seccion == "" || $num_seccion == "**")
                {
                    die($indice.", No se encontro la sección en la fila x. : ".$nom_alumno);
                }
            
                $institucion = $instituciones->where('num_institucion', $num_institucion)->first();
                if($institucion == null)
                {
                    die($num_institucion);
                }
                $prueba = new Prueba();
                $prueba->cod_institucion = $institucion->cod_institucion;
                $prueba->nom_alumno = $nom_alumno;
                $prueba->num_seccion = (int)$num_seccion;
                $prueba->save();

                $consolidado = new Consolidado();
                $consolidado->cod_evaluacion = $cod_evaluacion;
                $consolidado->nom_ugel = $institucion->ugel->nom_ugel;
                $consolidado->nom_institucion = $institucion->nom_institucion;
                $consolidado->num_peso_total = $num_peso_total;   //suma detalle evaluacion num_peso
                $prueba->consolidado()->save($consolidado);

                $num_nota = 0;
                for($i=0; $i<25; $i++)
                {
                    $num_respuesta_correcta = $evaluacion->detalle->where('num_pregunta', $i + 1)->first()->num_respuesta;
                    $num_respuesta_marcada = (int)$nota[$i];
                    if($num_respuesta_correcta == $num_respuesta_marcada)
                    {
                        $num_nota = (int)$num_nota + (int)$num_respuesta_correcta;
                    }
                }

                $nom_comentario = $puntajes
                    ->where('num_minimo', '<=', $num_nota)
                    ->where('num_maximo', '>=', $num_nota)
                    ->first()
                    ->nom_comentario;

                $consolidadoCabeza = new ConsolidadoCabeza();
                $consolidadoCabeza->nom_alumno = $nom_alumno;
                $consolidadoCabeza->num_nota = $num_nota;   //25 pregunta , sumar las respuestas acertadas con el detalle de evaluacion
                $consolidadoCabeza->nom_comentario = $nom_comentario; //de puntaje
                $consolidadoCabeza->num_seccion = (int)$num_seccion;
              
                $consolidado->cabeza()->save($consolidadoCabeza);

                for($i=1; $i<=25; $i++) {

                    $pruebaDetalle = new PruebaDetalle();

                    $evalucionDetalle = $evaluacion->detalle->where('num_pregunta', $i)->first();
                    $pruebaDetalle->cod_evaluacion_detalle = $evalucionDetalle->cod_evaluacion_detalle;
                    $pruebaDetalle->num_respuesta = (int)$nota[$i - 1];
                    $prueba->detalle()->save($pruebaDetalle);

                    $consolidadoCuerpo = new ConsolidadoCuerpo();
                    $consolidadoCuerpo->num_pregunta = $i;
                    $consolidadoCuerpo->num_respuesta = (int)$nota[$i - 1];
                    $consolidadoCabeza->cuerpo()->save($consolidadoCuerpo);
                }
  
            }

        }

        DB::commit();

        return redirect('/cargar_evaluacion');
    }
}