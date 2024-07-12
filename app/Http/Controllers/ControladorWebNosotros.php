<?php

namespace App\Http\Controllers;

use App\Entidades\Postulacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

require app_path() . '/start/constants.php';

class ControladorWebNosotros extends Controller
{
    public function index()
    {
        return view('web.nosotros');
    }

    public function postular(Request $request)
    {
        $request->validate([
            'txtNombre' => 'required|string',
            'txtApellido' => 'required|string',
            'txtEmail' => 'required|email',
            'txtDomicilio' => 'required|string',
            'txtTelefono' => 'required|string',
            'fileCV' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $file = $request->file('fileCV');

        $postulacion = new Postulacion();
        $postulacion->cargarDesdeRequest($request);

        try {
            $path = $file->storePublicly('cv');
        } catch (Exception $e) {
            Session::now('msg', [
                "ESTADO" => MSG_ERROR,
                "MSG" => "Ocurrió un error al cargar el archivo."
            ]);

            return view('web.nosotros');
        }

        $postulacion->archivo = basename($path);

        try {
            $postulacion->save();
        } catch (Exception $e) {
            Storage::delete($path);

            Session::now('msg', [
                "ESTADO" => MSG_ERROR,
                "MSG" => "Ocurrió un error al cargar la postulación."
            ]);

            return view('web.nosotros');
        }

        $page = 'nosotros';
        $titulo = 'Postulación recibida';
        $mensaje = 'Nos pondremos en contacto.';
        return view('web.mensaje', compact('page', 'titulo', 'mensaje'));
    }
}
