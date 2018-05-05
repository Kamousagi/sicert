<?php

namespace App\Http\Controllers;

use App\Evaluacion;
use App\Http\Controllers\Controller;

class EvaluacionController extends Controller
{
    public function index()
    {
        return view('aplicacion.evaluaciones.index', ['evaluaciones' => Evaluacion::all()]);
    }
}