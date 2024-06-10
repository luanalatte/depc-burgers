<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require app_path() . '/start/constants.php';
require app_path() . '/start/funciones_generales.php';

class ControladorWebLogin extends Controller
{
    public function index()
    {
        return view('web.login');
    }

    public function logout()
    {
        Session::flush();
        return redirect('');
    }

    public function login(Request $request)
    {
        // TODO: usar algo mejor que fescape_string?
        $emailIngresado = fescape_string($request->input('txtEmail'));
        $claveIngresada = fescape_string($request->input('txtClave'));

        $cliente = Cliente::obtenerPorEmail($emailIngresado);

        if (is_null($cliente) || !$cliente->verificarClave($claveIngresada)) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "Email o clave incorrectos.";
            return view('web.login', compact('msg'));
        }

        Session::put('cliente_id', $cliente->idcliente);
        Session::put('cliente_email', $cliente->email);
        Session::put('cliente_nombre', $cliente->nombre . " " . $cliente->apellido);

        // TODO: Agregar fecha de ultimo ingreso
        // $cliente->actualizarFechaIngreso();

        return redirect('');
    }
}
