<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require app_path() . '/start/constants.php';
require app_path() . '/start/funciones_generales.php';

class ControladorWebMiCuenta extends Controller
{
    public function index(Request $request)
    {
        $cliente = Cliente::miCuenta()->find(Cliente::autenticado());
        $aPedidos = $cliente->pedidosActivos;
        return view('web.micuenta', compact('cliente', 'aPedidos'));
    }

    public function guardar(Request $request)
    {
        $cliente = Cliente::miCuenta()->find(Cliente::autenticado());
        $aPedidos = $cliente->pedidosActivos;

        Session::now('msg', [
            'ESTADO' => MSG_SUCCESS,
            'MSG' => 'Guardado correctamente.'
        ]);

        return view('web.micuenta', compact('cliente', 'aPedidos'));
    }
}
