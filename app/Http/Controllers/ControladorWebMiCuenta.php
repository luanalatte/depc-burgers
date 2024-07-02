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

    public function getCambiarClave()
    {
        return view('web.cambiar-clave');
    }

    public function postCambiarClave(Request $request)
    {
        $cliente = Cliente::select('idcliente', 'clave')->find(Cliente::autenticado());

        if (!$request->filled(['txtClaveAntigua', 'txtClave1', 'txtClave2'])) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => 'Por favor ingresa todos los campos requeridos.'
            ]);

            return view('web.cambiar-clave');
        }

        $claveAntigua = $request->input('txtClaveAntigua');
        if (!password_verify($claveAntigua, $cliente->clave)) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => 'La clave antigua no es correcta.'
            ]);

            return view('web.cambiar-clave');
        }

        $clave1 = $request->input('txtClave1');
        $clave2 = $request->input('txtClave2');

        if ($clave1 != $clave2) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => 'Las claves ingresadas no son iguales.'
            ]);

            return view('web.cambiar-clave');
        }

        $cliente->clave = password_hash($clave1, PASSWORD_DEFAULT);
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
