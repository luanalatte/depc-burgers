<?php

namespace App\Http\Middleware;

use App\Entidades\Sistema\Patente;
use Closure;
use Illuminate\Http\Request;

class SistemaAutorizarOperacion
{
    public function handle(Request $request, Closure $next, string $codigo)
    {
        if (!Patente::autorizarOperacion($codigo)) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return response(view("sistema.error", compact("msg")));
        }

        return $next($request);
    }
}
