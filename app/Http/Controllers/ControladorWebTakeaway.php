<?php

namespace App\Http\Controllers;

use App\Entidades\Carrito;
use App\Entidades\Categoria;
use App\Entidades\Producto;
use Illuminate\Support\Facades\Session;

class ControladorWebTakeaway extends Controller
{
    public function index()
    {
        if (Session::get('cliente_id')) {
            $carrito = Carrito::select('idcarrito')->latest('idcarrito')->firstWhere('fk_idcliente', Session::get('cliente_id'));
            $aProductos = Producto::takeaway(!is_null($carrito) ? $carrito->idcarrito : null)->get();
        } else {
            $aProductos = Producto::takeaway()->get();
        }

        $aCategorias = Categoria::get(['idcategoria', 'nombre']);
        return view('web.takeaway', compact('aProductos', 'aCategorias'));
    }
}
