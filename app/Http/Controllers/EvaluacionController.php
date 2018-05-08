<?php

namespace App\Http\Controllers;

use App\Evaluacion;
use App\EvaluacionDetalle;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Validator;

class EvaluacionController extends Controller
{
    public function index()
    {
        $evaluaciones = Evaluacion::all();       
        return view('aplicacion.evaluaciones.index', ['evaluaciones' => $evaluaciones]);
    }

    public function getNuevo()
    {
        $evaluacion = [
            'cod_evaluacion' => 0,
            'num_grado' => 0,
            'num_anio' => 0,
            'num_correlativo' => 0,
            'num_tipo' => 0,
            'fec_fecha' => '',
            'ind_procesado' => "NO PROCESADO"
        ];

        for($i=0; $i<25; $i++)
        {
            $evaluacion["detalle"][] = [
                'num_respuesta' => null,
                'nom_mensaje' => null,
                'num_peso' => null,
            ];
        }
        return view('aplicacion.evaluaciones.mantenimiento', ['evaluacion' => $evaluacion]);
    }

    public function postGuardar(Request $solicitud) {

        $messages = [
            'num_grado.required' => 'Seleccione un grado',
            'num_anio.required' => 'Seleccione un año',
            'num_tipo.required' => 'Seleccione un tipo',
            'fec_fecha.required' => 'Seleccione una fecha de evaluación'
        ];

        for ($i=0; $i<25; $i++)
        {
            $messages['num_respuesta.'.$i.'.required'] = "Seleccione una respuesta para el detalle ".($i+1);
            $messages['nom_mensaje.'.$i.'.required'] = "Seleccione un mensaje para el detalle ".($i+1);
            $messages['num_peso.'.$i.'.required'] = "Seleccione un peso para el detalle ".($i+1);
        }

        $validator = Validator::make($solicitud->all(), [
            'num_grado' => 'required',
            'num_anio' => 'required',
            'num_tipo' => 'required',
            'fec_fecha' => 'required',
            'num_respuesta.*' => 'required',
            'nom_mensaje.*' => 'required',
            'num_peso.*' => 'required'
        ], $messages);

        $cod_evaluacion = $solicitud->input('cod_evaluacion');

        $redireccionar = "";
        if($cod_evaluacion == 0)
        {
            $redireccionar = "/evaluaciones/nuevo";
        } else {
            $redireccionar = "/evaluaciones/editar/".$cod_evaluacion;
        }
        if ($validator->fails()) {
            return redirect($redireccionar)
                        ->withErrors($validator)
                        ->withInput();
        }

        $num_grado = $solicitud->input('num_grado');
        $num_anio = $solicitud->input('num_anio');
        $num_tipo = $solicitud->input('num_tipo');
        $fec_fecha = $solicitud->input('fec_fecha');
    
        $num_respuesta = $solicitud->input('num_respuesta');
        $nom_mensaje = $solicitud->input('nom_mensaje');
        $num_peso = $solicitud->input('num_peso');
        
        if($cod_evaluacion == 0)
        {
            //es nuevo
            $evaluaciones = Evaluacion::where('num_anio', $num_anio)->get();
    
            $num_correlativo = 1;
            if($evaluaciones->count() > 0 ) {
                $num_correlativo = $evaluaciones->max('num_correlativo') + 1;
            }
    
            $evaluacion = new Evaluacion();
            $evaluacion->num_grado = $num_grado;
            $evaluacion->num_anio = $num_anio;
            $evaluacion->num_correlativo = $num_correlativo;
            $evaluacion->num_tipo = $num_tipo;
            $evaluacion->fec_fecha = $fec_fecha;
            $evaluacion->ind_procesado = 0;
            $evaluacion->save();

            for ($i=0; $i<25; $i++)
            {
                $evaluacionDetalle = new EvaluacionDetalle();
                $evaluacionDetalle->num_pregunta = $i + 1;
                $evaluacionDetalle->num_respuesta = $num_respuesta[$i];
                $evaluacionDetalle->nom_mensaje = $nom_mensaje[$i];
                $evaluacionDetalle->num_peso = $num_peso[$i];
                $evaluacion->detalle()->save($evaluacionDetalle);
            }

        } else {
            //se modifica
            $evaluacion = Evaluacion::with("detalle")->where('cod_evaluacion', $cod_evaluacion)->first();
            $evaluacion->num_grado = $num_grado;
            $evaluacion->num_anio = $num_anio;
            $evaluacion->num_tipo = $num_tipo;
            $evaluacion->fec_fecha = $fec_fecha;

            for ($i=0; $i<25; $i++)
            {
                $detalle = $evaluacion->detalle()->where('num_pregunta', ($i + 1))->first();
                $detalle->num_respuesta = $num_respuesta[$i];
                $detalle->nom_mensaje = $nom_mensaje[$i];
                $detalle->num_peso = $num_peso[$i];
                $detalle->save();
            }

            $evaluacion->save();
        }

        return redirect('/evaluaciones');
    }

    public function getEditar($cod_evaluacion) 
    {
        $entidad = Evaluacion::where('cod_evaluacion', $cod_evaluacion)->first();
        if(!$entidad)
        {
            die("no existe");
        }
        $evaluacion = [
            'cod_evaluacion' => $entidad->cod_evaluacion,
            'num_grado' => $entidad->num_grado,
            'num_anio' => $entidad->num_anio,
            'num_correlativo' => $entidad->num_correlativo,
            'num_tipo' => $entidad->num_tipo,
            'fec_fecha' => $entidad->fec_fecha,
            'ind_procesado' => $entidad->ind_procesado == 0 ? "NO PROCESADO" : "PROCESADO"
        ];

        foreach($entidad->detalle()->get() as $detalle)
        {
            $evaluacion["detalle"][] = [
                'num_respuesta' => $detalle->num_respuesta,
                'nom_mensaje' => $detalle->nom_mensaje,
                'num_peso' => $detalle->num_peso,
            ];
        }

        return view('aplicacion.evaluaciones.mantenimiento', ['evaluacion' => $evaluacion]);
    }
}