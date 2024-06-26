<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require app_path() . '/start/constants.php';
require app_path() . '/start/funciones_generales.php';

class ControladorWebRegistro extends Controller
{
    public function index()
    {
        if (Cliente::autenticado()) {
            redirect('');
        }

        return view('web.register');
    }

    public function guardar(Request $request)
    {
        if (Cliente::autenticado()) {
            redirect('');
        }

        return redirect('micuenta');
    }
}
