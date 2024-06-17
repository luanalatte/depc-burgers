<?php

namespace App\Http\Middleware;

use App\Entidades\Sistema\Patente;
use Closure;
use Illuminate\Http\Request;

class SistemaAutorizarOperacionJSON
{
    public function handle(Request $request, Closure $next, string $codigo)
    {
        if (!Patente::autorizarOperacion($codigo)) {
            $aResponse["err"] = EXIT_FAILURE;
            $aResponse["msg"] = "No tiene permisos para la operación ($codigo).";
            return response(json_encode($aResponse));
        }

        return $next($request);
    }
}
