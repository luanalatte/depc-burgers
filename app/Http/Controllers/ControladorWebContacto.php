<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require app_path() . '/start/constants.php';

class ControladorWebContacto extends Controller
{
    public function index()
    {
        return view('web.contacto');
    }

    public function contactar(Request $request)
    {
        Session::now( 'msg', [
            "ESTADO" => MSG_SUCCESS,
            "MSG" => "Gracias por ponerte en contacto con nosotros. Recibirás una respuesta pronto."
        ]);

        return view('web.contacto');
    }
}
