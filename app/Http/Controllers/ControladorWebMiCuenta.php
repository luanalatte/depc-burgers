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
        if (!Cliente::autenticado()) {
            return redirect('/login');
        }

        $cliente = Cliente::obtenerPorId(Session::get('cliente_id'));
        return view('web.micuenta', compact('cliente'));
    }
}
