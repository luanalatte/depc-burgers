<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Exception;
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
        $cliente->cargarDesdeRequest($request);
        
        if (!$request->filled(['txtNombre', 'txtApellido', 'txtDocumento', 'txtEmail'])) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => 'Por favor ingresa todos los campos requeridos.'
            ]);
        } else {
            try {
                $cliente->save();
                Session::now('msg', [
                    'ESTADO' => MSG_SUCCESS,
                    'MSG' => 'Guardado correctamente.'
                ]);
            } catch (Exception $e) {
                Session::now('msg', [
                    'ESTADO' => MSG_ERROR,
                    'MSG' => 'Ocurrió un error al guardar tus datos.'
                ]);
            }
        }

        $aPedidos = $cliente->pedidosActivos;

        return view('web.micuenta', compact('cliente', 'aPedidos'));
    }
}
