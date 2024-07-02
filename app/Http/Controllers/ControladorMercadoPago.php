<?php

namespace App\Http\Controllers;

use MercadoPago\MercadoPagoConfig;

use App\Entidades\Cliente;
use App\Entidades\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

require app_path() . '/start/constants.php';

class ControladorMercadoPago extends Controller
{
    private function autenticar()
    {
        MercadoPagoConfig::setAccessToken(env('MP_TOKEN'));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    private function crearPreferenceRequest($idpedido)
    {
        $cliente = Cliente::find(Cliente::autenticado());
        $pedido = Pedido::find($idpedido);

        $items = [];
        foreach ($pedido->productos as $producto) {
            $items[] = [
                'id' => $producto->idproducto,
                'title' => $producto->nombre,
                'description' => $producto->descripcion,
                'currency_id' => "ARS",
                'unit_price' => $producto->precio,
                'quantity' => $producto->pivot->cantidad
            ];
        }

        $payer = [
            "name" => $cliente->nombre,
            "surname" => $cliente->apellido,
            "email" => $cliente->email,
            "identification" => [
                "type" => "DNI",
                "number" => $cliente->documento
            ]
        ];

        $back_urls = [
            "success" => route('mercadopago.aprobado', [$pedido->idpedido]),
            "failure" => route('mercadopago.error', [$pedido->idpedido]),
            "pending" => route('mercadopago.pendiente', [$pedido->idpedido])
        ];

        return [
            "items" => $items,
            "payer" => $payer,
            "back_urls" => $back_urls,
            "auto_return" => "approved"
        ];
    }

    public function pagar(Request $request)
    {
        $this->autenticar();

        $request = $this->crearPreferenceRequest($request->id);
        $mpClient = new PreferenceClient();

        try {
            $preference = $mpClient->create($request);

            return Redirect::to($preference->init_point);
        } catch (MPApiException $e) {
            Session::now('msg', [
                'ESTADO' => MSG_ERROR,
                'MSG' => 'Ocurrió un error al generar el link de pago.'
            ]);

            return redirect('/micuenta');
        }
    }

    public function aprobado(Request $request)
    {
        $pedido = Pedido::find($request->id);
        $pedido->fk_idestado = 1;
        $pedido->pagado = true;
        $pedido->save();
        return redirect("/micuenta");
    }

    public function pendiente(Request $request)
    {
        $pedido = Pedido::find($request->id);
        $pedido->fk_idestado = 1;
        $pedido->pagado = false;
        $pedido->save();
        return redirect("/micuenta");
    }

    public function error(Request $request)
    {
        $pedido = Pedido::find($request->id);
        $pedido->fk_idestado = 1;
        $pedido->pagado = false;
        $pedido->save();
        return redirect("/micuenta");
    }
}
