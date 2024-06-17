<?php

namespace App\Http\Middleware;

use App\Entidades\Sistema\Usuario;
use Closure;
use Illuminate\Http\Request;

class SistemaAutenticar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Usuario::autenticado()) {
            return redirect("/admin/login");
        }

        return $next($request);
    }
}
