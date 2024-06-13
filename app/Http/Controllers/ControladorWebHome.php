<?php

namespace App\Http\Controllers;

use App\Entidades\Categoria;
use App\Entidades\Producto;
use App\Entidades\Sucursal;

class ControladorWebHome extends Controller
{
    public function index()
    {
        $aSucursales = Sucursal::all();
        return view('web.index', compact('aSucursales'));
    }

    public function takeaway()
    {
        $aProductos = Producto::orderBy('fk_idcategoria')->get(); // TODO: Usar orden de la categoría
        $aCategorias = Categoria::all();
        return view('web.takeaway', compact('aProductos', 'aCategorias'));
    }

    public function nosotros()
    {
        return view('web.nosotros');
    }

    public function contacto()
    {
        return view('web.contacto');
    }
}
