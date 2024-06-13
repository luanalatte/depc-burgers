<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Illuminate\Http\Request;

require app_path() . '/start/constants.php';
require app_path() . '/start/funciones_generales.php';

class ControladorWebMiCuenta extends Controller
{
    public function index(Request $request)
    {
        if (!$cliente = Cliente::autenticado()) {
            return redirect('/login');
        }

        return view('web.micuenta', compact('cliente'));
    }
}
