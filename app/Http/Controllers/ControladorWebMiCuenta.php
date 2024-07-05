<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
        $idcliente = Cliente::autenticado();

        $request->validate([
            'txtNombre' => 'required|string',
            'txtApellido' => 'required|string',
            'txtEmail' => ['required', 'email', Rule::unique('clientes', 'email')->ignore($idcliente, 'idcliente')],
            'txtDocumento' => 'required|string',
            'txtTelefono' => 'nullable|string'
        ]);

        $cliente = Cliente::miCuenta()->find($idcliente);
        $cliente->cargarDesdeRequest($request);

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

        $aPedidos = $cliente->pedidosActivos;

        return view('web.micuenta', compact('cliente', 'aPedidos'));
    }

    public function getCambiarClave()
    {
        return view('web.cambiar-clave');
    }

    public function postCambiarClave(Request $request)
    {
        $cliente = Cliente::select('idcliente', 'clave')->find(Cliente::autenticado());

        $request->validate([
            'txtClaveAntigua' => 'required|string',
            'txtClave' => ['required', 'confirmed', 'string', Password::min(8)->mixedCase()->numbers()]
        ]);

        if (!password_verify($request->txtClaveAntigua, $cliente->clave)) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => 'La clave actual no es correcta.'
            ]);

            return view('web.cambiar-clave');
        }

        $cliente->clave = password_hash($request->txtClave, PASSWORD_DEFAULT);
        try {
            $cliente->save();
        } catch (Exception $e) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => 'Ocurrió un error al actualizar tu clave. No se ha modificado.'
            ]);

            return view('web.cambiar-clave');
        }

        $page = 'cambiar-clave';
        $titulo = 'Clave cambiada';
        $mensaje = "Tu clave ha sido cambiada exitosamente";
        return view('web.mensaje', compact('page', 'titulo', 'mensaje'));
    }
}
