<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Prueba;
use App\PruebaDetalle;
use App\Evaluacion;
use App\EvaluacionDetalle;
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

<<<<<<< HEAD
        $evaluaciones = ["asdf" => "asdf", "xxx" => "yyy"];
        /*
        $evaluaciones = [];
        foreach (Evaluacion::all() as $evaluacion) {
            // $tmp = new SeleccionableModelo();
            // $tmp->codigo = $evaluacion->cod_evaluacion;
            // $tmp->descripcion = $evaluacion->cod_evaluacion;
            //$tmp[];
            //$tmp[] = "asdf" => "asdfas";
            //$evaluaciones[] = ("asdf" => "asdfas" );
            //$evaluaciones[] = "asdfs" => "asdfas";
            //array_push($evaluaciones, ("asdf" => "asdfasd"));
        }
        */
=======
        $evaluaciones = Evaluacion::select(
            DB::raw("CONCAT(NUM_ANIO,' ',NUM_CORRELATIVO) AS descripcion"),'cod_evaluacion')
            ->pluck('descripcion', 'cod_evaluacion');

>>>>>>> 9e904f4d01d23e83a4ef0e3974b26bb8a8ca8eb8
        return view('aplicacion.cargar_evaluacion.index', ['modelo' => $modelo, 'evaluaciones' => $evaluaciones]);
    }

    public function guardar(Request $solicitud) {

        $cod_evaluacion = $solicitud->input('cod_evaluacion');
        $evaluacion = Evaluacion::where('cod_evaluacion', $cod_evaluacion)->with('detalle')->get();
        //die($evaluacion);
        $archivo = $solicitud->file('archivo');

        DB::beginTransaction();

        if ($solicitud->hasFile('archivo')) {
            $data = file($archivo->getRealPath());
            $indice = 0;
            foreach($data as $linea) {
                $indice++;
                $nom_alumno = trim(substr($linea, 40, 14))." ".trim(substr($linea, 54, 12));
                $num_seccion = substr($linea, 90, 2);



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

                    for($i=1; $i<=25; $i++) {
                        //die($i);
                        $nota = substr($linea, 91 + $i, 1);
                        if($nota == "*")
                        {
                            DB::rollBack();
                            die("no se encontro nota");
                        }
                        $pruebaDetalle = new PruebaDetalle();
                        $detalle = $evaluacion->detalle()->where('num_pregunta', '=', $i)->first();
                        die($detalle);
                        $pruebaDetalle->cod_evaluacion_detalle = $detalle->cod_evaluacion_detalle;
                        $pruebaDetalle->num_respuesta = (int)$nota;
                        $prueba->detalle()->save($pruebaDetalle);

                    }

                }


            }
        }

        //
        DB::commit();

        return redirect('/cargar_evaluacion');
    }
}