<?php

namespace App\Http\Controllers;

use App\Entidades\Categoria;
use App\Entidades\Producto;
use App\Entidades\Sistema\Patente;
use App\Entidades\Sistema\Usuario;
use Session;

class ControladorWebHome extends Controller
{
    public function index()
    {
        return view('web.index');
    }

    public function takeaway()
    {
        $aProductos = Producto::obtenerTodos();
        $aCategorias = Categoria::obtenerTodos();
        return view('web.takeaway', compact("aProductos", "aCategorias"));
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
