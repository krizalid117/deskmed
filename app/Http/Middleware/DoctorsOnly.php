<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DoctorsOnly
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
        if (in_array(Auth::user()["attributes"]["id_tipo_usuario"], [1, 2]) === false) { //Si no es doctor ni administrador...
            return redirect()->route('home');
        }

        return $next($request);
    }
}
