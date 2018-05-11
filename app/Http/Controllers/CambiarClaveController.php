<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Validator;

class CambiarClaveController extends Controller
{
    public function getIndex()
    {
        $datos = [
            "usuario" => "",
            "clave_actual" => "",
            "nueva_clave" => "",
            "confirmar_nueva_clave" => "",
        ];
        
        return view('aplicacion.cambiar_clave.index', ["formulario" => $datos]);
    }

    public function postIndex(Request $solicitud)
    {

        $messages = [
            'usuario.required' => 'Ingrese su usuario.',
            'clave_actual.required' => 'Ingrese su clave actual.',
            'nueva_clave.required' => 'Ingrese su nueva clave.',
            'confirmar_nueva_clave.required' => 'Ingrese la confirmación de su nueva clave.'
        ];

        $validator = Validator::make($solicitud->all(), [
            'usuario' => 'required',
            'clave_actual' => 'required',
            'nueva_clave' => 'required',
            'confirmar_nueva_clave' => 'required'
        ], $messages);

        $usuario = $solicitud->input('usuario');
        $clave_actual = $solicitud->input('clave_actual');
        $nueva_clave = $solicitud->input('nueva_clave');
        $confirmar_nueva_clave = $solicitud->input('confirmar_nueva_clave');

        if($nueva_clave != $confirmar_nueva_clave) {
            $error = true;
            $mensaje_error = "La nuevas claves no coinciden.";
            return redirect('/cambiar_clave')->withErrors(["errors" => [$mensaje_error]])->withInput();
        }

        $usuario = User::
            where('nom_usuario', $usuario)
            ->where('nom_clave', $clave_actual)->first();

        $error = false;
        $mensaje_error = "";

        if($usuario == null){
            $error = true;
            $mensaje_error = "No se encontro al usuario.";
        }

        if($error == true){
            return redirect('/cambiar_clave')->withErrors(["errors" => [$mensaje_error]])->withInput();
        }

        if ($validator->fails()) {
            return redirect('/cambiar_clave')
                        ->withErrors($validator)
                        ->withInput();
        }

        $usuario->nom_clave = $nueva_clave;
        $usuario->save();

        return redirect('/cambiar_clave')->with('exito', 'La carga de evaluación se realizo correctamente');
    }
}