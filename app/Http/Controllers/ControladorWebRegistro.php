<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

require app_path() . '/start/constants.php';
require app_path() . '/start/funciones_generales.php';

class ControladorWebRegistro extends Controller
{
    public function index()
    {
        if (Session::has('cliente_id')) {
            return redirect('/');
        }

        return view('web.register');
    }

    public function guardar(Request $request)
    {
        if (Session::has('cliente_id')) {
            return redirect('/');
        }

        $request->validate([
            'txtNombre' => 'required|string',
            'txtApellido' => 'required|string',
            'txtEmail' => 'required|email|unique:clientes,email',
            'txtDocumento' => 'required|string',
            'txtClave' => ['required', 'confirmed', 'string', Password::min(8)->mixedCase()->numbers()],
            'txtTelefono' => 'nullable|string'
        ]);

        $cliente = new Cliente();
        $cliente->cargarDesdeRequest($request);

        try {
            $cliente->save();
        } catch (Exception $e) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => "No se pudo crear la cuenta."
            ]);

            return view('web.register');
        }

        Session::put('cliente_id', $cliente->idcliente);
        Session::put('cliente_email', $cliente->email);
        Session::put('cliente_nombre', $cliente->nombre . " " . $cliente->apellido);
        return redirect('/');
    }
}
