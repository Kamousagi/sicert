<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
//use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class AutenticacionController extends Controller
{
    public function index()
    {
        // Verificamos que el usuario no esté autenticado
        if (Auth::check())
        {
            // Si está autenticado lo mandamos a la raíz donde estara el mensaje de bienvenida.
            return Redirect::to('/');
        }
        // Mostramos la vista login.blade.php (Recordemos que .blade.php se omite.)
        return view('autenticacion.login');
    }

    public function login(Request $request)
    {

        // Guardamos en un arreglo los datos del usuario.
        // $userdata = array(
        //     'nom_usuario' => $request->input('nom_usuario'),
        //     'nom_clave'=> $request->input('nom_clave')
        // );

        //$credentials = $request->only('nom_usuario', 'nom_clave');
        $nom_usuario = $request->input('nom_usuario');
        $nom_clave = $request->input('nom_clave');

        // Validamos los datos y además mandamos como un segundo parámetro la opción de recordar el usuario.
        //if(Auth::attempt($userdata, $request->input('remember-me')))
        //if(Auth::attempt($credentials))
        //die($nom_usuario);
        
        $auth = User::
            where('nom_usuario', '=', $nom_usuario)
            ->where('nom_clave', '=', $nom_clave)->first();
        
        //if (Auth::attempt(['nom_usuario' => $nom_usuario, 'nom_clave' => $nom_clave]))
        if ($auth)
        {
            // De ser datos válidos nos mandara a la bienvenida
            Auth::login($auth);
            return Redirect::to('/');
        }
        // En caso de que la autenticación haya fallado manda un mensaje al formulario de login y también regresamos los valores enviados con withInput().
        return Redirect::to('/login')
                    ->with('mensaje_error', 'Tus datos son incorrectos')
                    ->withInput();

        //return view('autenticacion.login');
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('/login')
                    ->with('mensaje_error', 'Tu sesión ha sido cerrada.');
        //return redirect('/login');
    }

}