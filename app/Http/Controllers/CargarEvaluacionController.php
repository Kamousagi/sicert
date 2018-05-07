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
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            ->pluck('descripcion', 'cod_evaluacion');

        return view('aplicacion.cargar_evaluacion.index', ['modelo' => $modelo, 'evaluaciones' => $evaluaciones]);
    }

    public function guardar(Request $solicitud) {

        $cod_evaluacion = $solicitud->input('cod_evaluacion');
        $archivo = $solicitud->file('archivo');

        DB::beginTransaction();

        if ($solicitud->hasFile('archivo')) {

            $data = file($archivo->getRealPath());
            $indice = 0;

            foreach($data as $linea) {

                $indice++;
                $nom_alumno = trim(substr($linea, 40, 14))." ".trim(substr($linea, 54, 12));
                $num_seccion = substr($linea, 90, 2);
                $num_institucion = substr($linea, 74, 7);

                $institucion = Institucion::where('num_institucion', $num_institucion)->first();
                $utel = Ugel::where('cod_ugel', $institucion->cod_ugel)->first();

                if($num_seccion == "" || $num_seccion == "**")
                {
                    die($indice.", No se encontro la sección en la fila x. : ".$nom_alumno);
                } else {
                    $prueba = new Prueba();
                    $prueba->cod_prueba = 0;
                    $prueba->cod_institucion = 1;
                    $prueba->nom_alumno = $nom_alumno;
                    $prueba->num_seccion = (int)$num_seccion;
                    $prueba->save();

                    $consolidado = new Consolidado();
                    $consolidado->cod_evaluacion = $cod_evaluacion;
                    $consolidado->nom_ugel = $utel->nom_ugel;
                    $consolidado->nom_institucion = $institucion->nom_institucion;
                    $consolidado->num_peso_total = 0;
                    $prueba->consolidado()->save($consolidado);

                    $consolidadoCabeza = new ConsolidadoCabeza();
                    $consolidadoCabeza->nom_alumno = $nom_alumno;
                    $consolidadoCabeza->num_nota = 0;
                    $consolidadoCabeza->nom_comentario = "algun comentario";
                    $consolidado->cabeza()->save($consolidadoCabeza);

                    for($i=1; $i<=25; $i++) {
                        $nota = substr($linea, 91 + $i, 1);
                        if($nota == "*")
                        {
                            DB::rollBack();
                            die("no se encontro nota");
                        }
                        $pruebaDetalle = new PruebaDetalle();
                        $detalle = EvaluacionDetalle::
                            where('cod_evaluacion', $cod_evaluacion)
                            ->where('num_pregunta', $i)
                            ->first();
                        $pruebaDetalle->cod_evaluacion_detalle = $detalle->cod_evaluacion_detalle;
                        $pruebaDetalle->num_respuesta = (int)$nota;
                        $prueba->detalle()->save($pruebaDetalle);

                        $consolidadoCuerpo = new ConsolidadoCuerpo();
                        $consolidadoCuerpo->num_pregunta = $i;
                        $consolidadoCuerpo->num_respuesta = $detalle->num_respuesta;
                        $consolidadoCabeza->cuerpo()->save($consolidadoCuerpo);
                    }

                }

            }

        }

        //
        DB::commit();

        return redirect('/cargar_evaluacion');
    }
}