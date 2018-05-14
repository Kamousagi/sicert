<?php

namespace App\Http\Middleware;

use Closure;

class AdministradorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $usuarioLogeado = \Auth::User();
        if($usuarioLogeado->num_tipo != 1){
            return redirect('/sin_permisos');
        }
        return $next($request);
    }
}