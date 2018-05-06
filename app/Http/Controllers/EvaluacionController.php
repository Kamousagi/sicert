<?php

namespace App\Http\Controllers;

use App\Evaluacion;
use App\EvaluacionDetalle;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EvaluacionModelo {
    public $cod_evaluacion;
    public $num_grado;
    public $num_anio;
    public $num_correlativo;
    public $num_tipo;
}

class EvaluacionDetalleModelo {
    public $cod_evaluacion_detalle;
    public $num_pregunta;
    public $num_respuesta;
    public $nom_mensaje;
}

class EvaluacionController extends Controller
{
    public function index()
    {
        return view('aplicacion.evaluaciones.index', ['evaluaciones' => Evaluacion::all()]);
    }

    public function nuevo()
    {
        $evaluacion = new EvaluacionModelo();
        $evaluacion->cod_evaluacion = 0;
        return view('aplicacion.evaluaciones.nuevo', ['evaluacion' => $evaluacion]);
    }

    public function guardar(Request $solicitud) {

        // $validacion = Validator::make($solicitud->all(), [
        //     'cod_evaluacion' => 'required',
        //     'num_grado' => 'required',
        //     'num_anio' => 'required',
        //     'num_correlativo' => 'required',
        //     'num_tipo' => 'required'
        // ]);

        $this->validate($solicitud, [
            'cod_evaluacion' => 'required',
            'num_grado' => 'required',
            'num_anio' => 'required',
            'num_correlativo' => 'required',
            'num_tipo' => 'required'
        ]);

        // $validacion = $solicitud->validate([
        //     'cod_evaluacion' => 'required',
        //     'num_grado' => 'required',
        //     'num_anio' => 'required',
        //     'num_correlativo' => 'required',
        //     'num_tipo' => 'required'
        // ]);

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
        $entidad->save();

        for ($i = 0; $i < 25; $i++)
        {
            $entidadDetalle = new EvaluacionDetalle();
            $entidadDetalle->num_pregunta = $i;
            $entidadDetalle->num_respuesta = 1;
            $entidadDetalle->nom_mensaje = "";
            $entidadDetalle->num_peso = 2;
            $entidad->detalle()->save($entidadDetalle);
        }
        
        return redirect('/evaluaciones');
    }
}