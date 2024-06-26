<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require app_path() . '/start/constants.php';

class ControladorWebNosotros extends Controller
{
    public function index()
    {
        return view('web.nosotros');
    }

    public function postular(Request $request)
    {
        Session::now( 'msg', [
            "ESTADO" => MSG_SUCCESS,
            "MSG" => "Postulación recibida, nos pondremos en contacto."
        ]);

        return view('web.nosotros');
    }
}
