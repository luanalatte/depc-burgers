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
        if (!Cliente::autenticado()) {
            return redirect('/login');
        }

        $cliente = Cliente::obtenerPorId(Session::get('cliente_id'));
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
        if (!Cliente::autenticado()) {
            return redirect('/login');
        }

        $cliente = Cliente::obtenerPorId(Session::get('cliente_id'));
        $carrito = Carrito::obtenerPorCliente($cliente);
        if (is_null($carrito)) {
            $carrito = new Carrito();
            $carrito->fk_idcliente = $cliente->idcliente;
            $carrito->insertar();
        }

        $idproducto = $request->get('idproducto');
        $cantidad = $request->input('txtCantidad', 1);
        $carrito->agregarProducto($idproducto, $cantidad);


        // TODO: No redireccionar, solo notificar. Popup Toast?
        return redirect('/carrito');
    }

    public function editar(Request $request)
    {
        if (!Cliente::autenticado()) {
            return redirect('/login');
        }

        $cliente = Cliente::obtenerPorId(Session::get('cliente_id'));
        $carrito = Carrito::obtenerPorCliente($cliente);
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
