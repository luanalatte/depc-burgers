<?php

namespace App\Http\Controllers;

use App\Entidades\Sucursal;

class ControladorWebHome extends Controller
{
    public function index()
    {
        $aSucursales = Sucursal::get(['nombre', 'direccion', 'telefono', 'maps_url']);
        return view('web.index', compact('aSucursales'));
    }
}
