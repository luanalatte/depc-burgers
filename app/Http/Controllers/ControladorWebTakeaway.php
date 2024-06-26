<?php

namespace App\Http\Controllers;

use App\Entidades\Categoria;
use App\Entidades\Producto;

class ControladorWebTakeaway extends Controller
{
    public function index()
    {
        $aProductos = Producto::takeaway()->get();
        $aCategorias = Categoria::get(['idcategoria', 'nombre']);
        return view('web.takeaway', compact('aProductos', 'aCategorias'));
    }
}
