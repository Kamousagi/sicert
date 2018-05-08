<?php

namespace App\Http\Controllers;

use App\Evaluacion;
use App\EvaluacionDetalle;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Validator;

class EvaluacionModelo {
    public $cod_evaluacion;
    public $num_grado;
    public $num_anio;
    public $num_correlativo;
    public $num_tipo;
    public $detalle;
}

class EvaluacionDetalleModelo {
    public $cod_evaluacion_detalle;
    public $num_pregunta;
    public $num_respuesta;
    public $nom_mensaje;
    public $num_peso;
}

class EvaluacionController extends Controller
{
    public function index()
    {
        $evaluaciones = Evaluacion::all();       
        return view('aplicacion.evaluaciones.index', ['evaluaciones' => $evaluaciones]);
    }

    public function nuevo()
    {
        $evaluacion = new EvaluacionModelo();
        $evaluacion->cod_evaluacion = 0;

        for ($i=1; $i <= 25; $i++)
        {
            $detalle = new EvaluacionDetalleModelo();
            $detalle->num_pregunta = $i;
            $detalle->num_respuesta = 0;
            $detalle->nom_mensaje = "asdfasd";
            $detalle->num_peso = 0;
            $evaluacion->detalle[] = $detalle;
        }
        
        return view('aplicacion.evaluaciones.nuevo', ['evaluacion' => $evaluacion]);
    }

    public function guardar(Request $solicitud) {

        $validator = Validator::make($solicitud->all(), [
            'cod_evaluacion' => 'required',
            'num_grado' => 'required',
            'num_anio' => 'required',
            'num_correlativo' => 'required',
            'num_tipo' => 'required',
            'num_respuesta.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/evaluaciones/nuevo')
                        ->withErrors($validator)
                        ->withInput();
        }

        $modelo = new EvaluacionModelo();
        $modelo->cod_evaluacion = $solicitud->input('cod_evaluacion');
        $modelo->num_grado = $solicitud->input('num_grado');
        $modelo->num_anio = $solicitud->input('num_anio');
        $modelo->num_correlativo = $solicitud->input('num_correlativo');
        $modelo->num_tipo = $solicitud->input('num_tipo');

        $entidad = new Evaluacion();
        $entidad->cod_evaluacion = 0;
        $entidad->num_grado = $modelo->num_grado;
        $entidad->num_anio = $modelo->num_anio;
        $entidad->num_correlativo = $modelo->num_correlativo;
        $entidad->num_tipo = $modelo->num_tipo;
        $entidad->fec_fecha = date('Y-m-d H:i');
        $entidad->ind_procesado = 0;
        $entidad->save();

        $num_respuesta = $solicitud->input('num_respuesta');
        $nom_mensaje = $solicitud->input('nom_mensaje');
        $num_peso = $solicitud->input('num_peso');
        $numero = 0;
        for ($i = 0; $i < 25; $i++)
        {
            $entidadDetalle = new EvaluacionDetalle();
            $entidadDetalle->num_pregunta = $i + 1;
            $entidadDetalle->num_respuesta = $num_respuesta[$i];
            $entidadDetalle->nom_mensaje = $nom_mensaje[$i];
            $entidadDetalle->num_peso = $num_peso[$i];
            $entidad->detalle()->save($entidadDetalle);
        }

        return redirect('/evaluaciones');
    }
}