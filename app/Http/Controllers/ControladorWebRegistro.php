<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        if (!$request->filled(['txtNombre', 'txtApellido', 'txtEmail', 'txtDNI', 'txtClave', 'txtClave2'])) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => "Ingrese todos los campos requeridos."
            ]);

            return view('web.register');
        }

        // $nombre = $request->input('txtNombre');
        // $apellido = $request->input('txtApellido');
        $email = $request->input('txtEmail');
        // $documento = $request->input('txtDocumento');
        // $telefono = $request->input('txtTelefono');
        $clave1 = $request->input('txtClave');
        $clave2 = $request->input('txtClave2');

        if ($clave1 != $clave2) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => "Las claves ingresadas no son iguales."
            ]);

            return view('web.register');
        }

        $cliente = Cliente::firstOrNew(['email' => $email]);
        if ($cliente->exists) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => "El correo electrónico especificado ya está asociado a una cuenta."
            ]);

            return view('web.register');
        }

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
