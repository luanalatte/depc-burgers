<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;

require app_path() . '/start/constants.php';

class ControladorWebContacto extends Controller
{
    public function index()
    {
        return view('web.contacto');
    }

    public function contactar(Request $request)
    {
        $nombre = $request->input('txtNombre');
        $email = $request->input('txtEmail');
        $telefono = $request->input('txtTelefono');
        $mensaje = $request->input('txtMensaje');

        if(!$request->filled(['txtNombre', 'txtEmail', 'txtMensaje'])) {
            Session::now( 'msg', [
                "ESTADO" => MSG_ERROR,
                "MSG" => "Por favor ingrese todos los campos."
            ]);

            return view('web.contacto', compact('nombre', 'email', 'telefono', 'mensaje'));
        }

        $mailer = new PHPMailer(true);
        $mailer->SMTPDebug = 0;
        $mailer->isSMTP();
        $mailer->Host = env('MAIL_HOST');
        $mailer->SMTPAuth = true;
        $mailer->Username = env('MAIL_USERNAME');
        $mailer->Password = env('MAIL_PASSWORD');
        $mailer->SMTPSecure = env('MAIL_ENCRYPTION');
        $mailer->Port = env('MAIL_PORT');

        $mailer->setFrom(env('MAIL_FROM_ADDRESS'), env('APP_NAME'));
        $mailer->addAddress(env('MAIL_FROM_ADDRESS'));

        $mailer->isHTML(true);
        $mailer->Subject = 'Contato ' . env('APP_NAME');
        $mailer->Body = "<b>Nombre: </b>$nombre<br>";
        $mailer->Body .= "<b>Email: </b>$email<br>";
        if ($telefono) {
            $mailer->Body .= "<b>Teléfono: </b>$telefono<br>";
        }
        $mailer->Body .= "<br>$mensaje";

        try {
            // $mailer->send();
        } catch(Exception $e) {
            Session::now( 'msg', [
                "ESTADO" => MSG_ERROR,
                "MSG" => "Ocurrió un error al enviar el formulario."
            ]);

            return view('web.contacto', compact('nombre', 'email', 'telefono', 'mensaje'));
        }

        $page = 'contacto';
        $titulo = '¡Gracias por contactarnos!';
        $mensaje = 'Pronto estarás recibiendo una respuesta por email';
        return view('web.mensaje', compact('page', 'titulo', 'mensaje'));
    }
}
