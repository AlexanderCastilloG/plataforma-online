<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        //$role llega como un string y tenemos que parsialo como un entero
        if( auth()->user()->role_id !== (int) $role ) {
            abort(401, __("No puedes acceder a esta zona")); //Para abortar la petición
        }
        return $next($request);
    }
}
