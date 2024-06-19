<?php

namespace App\Http\Controllers;

use App\Entidades\Carrito;
use App\Entidades\Cliente;
use App\Entidades\Pedido;
use App\Entidades\Sucursal;
use Exception;
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

        $aSucursales = Sucursal::all();
        return view('web.carrito', compact('carrito', 'aSucursales'));
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
            return redirect('/carrito');
        }

        $idproducto = $request->get('idproducto');

        if (isset($_POST["btnEliminar"])) {
            $carrito->eliminarProducto($idproducto);
        }

        return redirect('/carrito');
    }

    public function confirmar(Request $request)
    {
        if (!$cliente = Cliente::autenticado()) {
            return redirect('/login');
        }

        $medioDePago = $request->input('lstMedioDePago');
        if (!$medioDePago) {
            Session::flash('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => "Selecciona un medio de pago."
            ]);

            return redirect('/carrito');
        }

        $sucursal = Sucursal::find($request->input('lstSucursal'));
        if (!$sucursal) {
            Session::flash('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => "La sucursal seleccionada no está disponible."
            ]);

            return redirect('/carrito');
        }

        $carrito = Carrito::cargarCompleto($cliente->idcliente);
        if (is_null($carrito) || !$carrito->aProductos) {
            return redirect('/carrito');
        }

        $pedido = new Pedido();
        $pedido->fk_idcliente = $carrito->fk_idcliente;
        $pedido->fk_idsucursal = $sucursal->idsucursal;

        // TODO: No hardcodear estados
        if ($medioDePago == "sucursal") {
            $pedido->fk_idestado = 1; // Estado de pedido pendiente de preparación.
        } else {
            $pedido->fk_idestado = 5; // Estado de pago pendiente.
        }
        
        $pedido->total = $carrito->total;
        $pedido->comentarios = $request->input('txtComentarios');
        
        try {
            // TODO: Generar pedido con estado oculto, y cambiarlo una vez hayan sido agregados todos los productos.
            $pedido->save();

            foreach ($carrito->aProductos as $producto) {
                $pedido->agregarProducto($producto['producto']->idproducto, $producto['cantidad']);
            }
        } catch (Exception $e) {
            Session::flash('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => "Algo salió mal generando tu pedido. Vuelve a intentar"
            ]);

            return redirect('/carrito');
        }

        return redirect("/pedido/$pedido->idpedido");
    }
}
