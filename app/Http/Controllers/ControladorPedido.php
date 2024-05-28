<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use App\Entidades\Estado;
use App\Entidades\Pedido;
use App\Entidades\Sistema\Usuario;
use App\Entidades\Sistema\Patente;
use App\Entidades\Sucursal;
use Exception;
use Illuminate\Http\Request;

require app_path() . '/start/constants.php';

class ControladorPedido extends Controller
{
    public function index()
    {
        $titulo = "Lista de Pedidos";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PEDIDOCONSULTA")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        return view("sistema.pedido-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nuevo Pedido";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PEDIDOALTA")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        $pedido = new Pedido();
        $aClientes = Cliente::obtenerTodos();
        $aSucursales = Sucursal::obtenerTodos();
        $aEstados = Estado::obtenerTodos();
        return view("sistema.pedido-nuevo", compact("titulo", "pedido", "aClientes", "aSucursales", "aEstados"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Pedido";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PEDIDOCONSULTA")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        if ($pedido = Pedido::obtenerPorId($request->id)) {
            $aEstados = Estado::obtenerTodos();
            return view("sistema.pedido-nuevo", compact("titulo", "pedido", "aEstados"));
        }

        $titulo = "Lista de Pedidos";
        $msg["ESTADO"] = MSG_ERROR;
        $msg["MSG"] = "El pedido especificado no existe.";
        return view("sistema.pedido-listar", compact("titulo", "msg"));
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Pedido";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        $entidad = new Pedido();
        $entidad->cargarDesdeRequest($request);

        try {
            if ($_POST["id"] > 0) {
                if (!Patente::autorizarOperacion($codigo = "PEDIDOEDITAR")) {
                    $mensaje = "No tiene pemisos para la operación.";
                    return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
                }

                $entidad->actualizar();
            } else {
                if (!Patente::autorizarOperacion($codigo = "PEDIDOALTA")) {
                    $mensaje = "No tiene pemisos para la operación.";
                    return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
                }

                if (empty($entidad->fk_idcliente) || empty($entidad->fk_idsucursal) || empty($entidad->fk_idestado) || empty($entidad->fecha) || empty($entidad->total)) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "Ingrese todos los datos requeridos.";
                } else {
                    $entidad->insertar();
                }
            }

            $_POST["id"] = $entidad->idpedido;

            $msg["ESTADO"] = MSG_SUCCESS;
            $msg["MSG"] = OKINSERT;

            return view("sistema.pedido-listar", compact("titulo", "msg"));
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        if (!Patente::autorizarOperacion($codigo = "PEDIDOCONSULTA")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        $pedido = Pedido::obtenerPorId($entidad->idpedido) ?? new Pedido(["idpedido"=>$entidad->idpedido]);
        $aClientes = Cliente::obtenerTodos();
        $aSucursales = Sucursal::obtenerTodos();
        $aEstados = Estado::obtenerTodos();
        return view("sistema.pedido-nuevo", compact("titulo", "msg", "pedido", "aClientes", "aSucursales", "aEstados"));
    }

    public function eliminar(Request $request)
    {
        $titulo = "Eliminar Pedido";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PEDIDOBAJA")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        try {
            $pedido = new Pedido();
            $pedido->idpedido = $request->id;
            $pedido->eliminar();

            $aResultado["err"] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $aResultado["err"] = "Error en la operación. No se pudo eliminar el pedido.";
        }

        echo json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
        if (!Usuario::autenticado() || !Patente::autorizarOperacion("PEDIDOCONSULTA"))
            return null;

        $count = Pedido::contarRegistros();
        $aSlice = Pedido::obtenerPaginado($request->start ?? 0, $request->length ?? 25);

        $data = [];
        foreach ($aSlice as $pedido) {
            $row = [];
            $row[] = '<a href="/admin/pedido/nuevo/' . $pedido->idpedido . '" class="btn btn-secondary">' . '<i class="fas fa-eye">' . '</a>';
            $row[] = $pedido->idpedido;
            $row[] = $pedido->cliente;
            $row[] = $pedido->sucursal;
            $row[] = $pedido->estado;
            $row[] = date("d/m/Y H:i", strtotime($pedido->fecha));
            $row[] = number_format($pedido->total, 2, ',', '.');
            $data[] = $row;
        }

        $json_data = [
            "draw" => intval($request['draw']),
            "recordsTotal" => $count, //cantidad total de registros sin paginar
            "recordsFiltered" => count($aSlice), //cantidad total de registros en la paginacion
            "data" => $data
        ];

        return json_encode($json_data);
    }
}
