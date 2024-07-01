<?php

namespace App\Http\Controllers;

use MercadoPago\MercadoPagoConfig;
MercadoPagoConfig::setAccessToken(env('MP_TOKEN'));
MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

use App\Entidades\Cliente;
use App\Entidades\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use MercadoPago\Client\Preference\PreferenceClient;

require app_path() . '/start/constants.php';
require app_path() . '/start/funciones_generales.php';

class ControladorMercadoPago extends Controller
{
    public function pagar(Request $request)
    {
        $cliente = Cliente::find(Cliente::autenticado());
        $pedido = Pedido::find($request->id);

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
            "email" => $cliente->email
        ];

        $mpClient = new PreferenceClient();
        $preference = $mpClient->create(["items" => $items, "payer" => $payer]);

        $preference->back_urls = [
            "success" => env("APP_URL") . "/mercadopago/correcto/$pedido->idpedido",
            "failure" => env("APP_URL") . "/mercadopago/error/$pedido->idpedido",
            "pending" => env("APP_URL") . "/mercadopago/pendiente/$pedido->idpedido"
        ];

        $preference->auto_return = "approved";

        return Redirect::to($preference->init_point);
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
