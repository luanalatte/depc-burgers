<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;

require app_path() . '/start/constants.php';
require app_path() . '/start/funciones_generales.php';

class ControladorWebLogin extends Controller
{
    public function index()
    {
        if (Cliente::autenticado()) {
            return redirect('/micuenta');
        }

        return view('web.login');
    }

    public function logout()
    {
        Session::flush(); // TODO: no usar flush?
        return redirect('/');
    }

    public function login(Request $request)
    {
        // TODO: usar algo mejor que fescape_string?
        $emailIngresado = fescape_string($request->input('txtEmail'));
        $claveIngresada = fescape_string($request->input('txtClave'));

        $cliente = Cliente::select('idcliente', 'email', 'clave', 'nombre', 'apellido')->firstWhere('email', $emailIngresado);

        if (is_null($cliente) || !password_verify($claveIngresada, $cliente->clave)) {
            Session::now('msg', [
                "ESTADO" => MSG_ERROR,
                "MSG" => "Email o clave incorrectos."
            ]);

            return view('web.login');
        }

        Session::put('cliente_id', $cliente->idcliente);
        Session::put('cliente_email', $cliente->email);
        Session::put('cliente_nombre', $cliente->nombre . " " . $cliente->apellido);

        // TODO: Agregar fecha de ultimo ingreso
        // $cliente->actualizarFechaIngreso();

        return redirect('/');
    }

    public function getRecuperarClave()
    {
        if (Session::has('cliente_id')) {
            return redirect('/');
        }

        return view('web.recuperar-clave');
    }

    public function postRecuperarClave(Request $request)
    {
        if (Session::has('cliente_id')) {
            return redirect('/');
        }

        $emailIngresado = fescape_string($request->input('txtEmail'));

        $cliente = Cliente::select('idcliente', 'email', 'clave')->firstWhere('email', $emailIngresado);

        $clave = "";
        if (!is_null($cliente)) {
            $clave = generarClaveAleatoria(8);
            $cliente->clave = password_hash($clave, PASSWORD_DEFAULT);

            $mailer = new PHPMailer(true);
            $mailer->SMTPDebug = 0;
            $mailer->isSMTP();
            $mailer->Host = env('MAIL_HOST');
            $mailer->SMTPAuth = true;
            $mailer->Username = env('MAIL_USERNAME');
            $mailer->Password = env('MAIL_PASSWORD');
            $mailer->SMTPSecure = env('MAIL_ENCRYPTION');
            $mailer->Port = env('MAIL_PORT');

            $mailer->setFrom(env('MAIL_NOREPLY'), env('APP_NAME'));
            $mailer->addAddress($cliente->email);

            $mailer->isHTML(true);
            $mailer->Subject = env('APP_NAME') . ': Recuperación de clave';
            $mailer->Body = "Tu nueva clave es: $clave";

            try {
                $cliente->save();
                // $mailer->send();
            } catch(Exception $e) {
                Session::now( 'msg', [
                    "ESTADO" => MSG_ERROR,
                    "MSG" => "Ocurrió un error al recuperar la clave."
                ]);

                return view('web.recuperar-clave');
            }
        }

        $page = 'recuperar-clave';
        $titulo = 'Hemos enviado un correo de recuperación';

        // TODO: No mostrar la clave aquí, enviar el mail.
        $mensaje = "Si la dirección de email ingresada pertenece a una cuenta, recibirás un correo con más instrucciones. ($clave)";
        return view('web.mensaje', compact('page', 'titulo', 'mensaje'));
    }
}
