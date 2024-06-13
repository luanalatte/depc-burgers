<?php

namespace App\Http\Controllers;

use App\Entidades\Carrito;
use App\Entidades\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require app_path() . '/start/constants.php';
require app_path() . '/start/funciones_generales.php';

class ControladorWebCarrito extends Controller
{
    public function index()
    {
        if (!$cliente = Cliente::autenticado()) {
            return redirect('/login');
        }

        $carrito = Carrito::cargarCompleto($cliente->idcliente);

        if (is_null($carrito)) {
            $carrito = new Carrito();
            $carrito->fk_idcliente = $cliente->idcliente;
            $carrito->insertar();
        }

        return view('web.carrito', compact('carrito'));
    }

    public function agregar(Request $request)
    {
        if (!$cliente = Cliente::autenticado()) {
            return redirect('/login');
        }

        $carrito = Carrito::firstOrCreate(['fk_idcliente' => $cliente->idcliente]);

        $idproducto = $request->get('idproducto');
        $cantidad = $request->input('txtCantidad', 1);
        $carrito->agregarProducto($idproducto, $cantidad);


        // TODO: No redireccionar, solo notificar. Popup Toast?
        return redirect('/carrito');
    }

    public function editar(Request $request)
    {
        if (!$cliente = Cliente::autenticado()) {
            return redirect('/login');
        }

        $carrito = Carrito::where('fk_idcliente', $cliente->idcliente)->first();
        if (is_null($carrito)) {
            return view('web.carrito');
        }

        $idproducto = $request->get('idproducto');

        if (isset($_POST["btnEliminar"])) {
            $carrito->eliminarProducto($idproducto);
        }

        return redirect('/carrito');
    }
}
