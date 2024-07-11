<?php

namespace App\Http\Middleware;

use App\Entidades\Sistema\Patente;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SistemaAutorizarOperacion
{
    public function handle(Request $request, Closure $next, string $codigo)
    {
        if (!Patente::autorizarOperacion($codigo)) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => "No tiene permisos para la operación ($codigo)."
            ]);

            return response(view("sistema.error"));
        }

        return $next($request);
    }
}
