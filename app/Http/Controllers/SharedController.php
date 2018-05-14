<?php

namespace App\Http\Controllers;

class SharedController extends Controller
{
    public function getSinPermisos()
    {
        return view('aplicacion.shared.sin_permisos');
    }

}