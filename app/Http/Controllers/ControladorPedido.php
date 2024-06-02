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
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $aEstados = Estado::obtenerTodos();
        $aSucursales = Sucursal::obtenerTodos();
        $countPedidos = Pedido::contarRegistros(); // TODO: Contar solo pedidos de la sucursal y/o período seleccionado actualmente.
        return view("sistema.pedido-listar", compact("titulo", "countPedidos", "aEstados", "aSucursales"));
    }

    public function nuevo()
    {
        $titulo = "Nuevo Pedido";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PEDIDOALTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
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
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        if ($pedido = Pedido::obtenerPorId($request->id)) {
            $aEstados = Estado::obtenerTodos();
            return view("sistema.pedido-nuevo", compact("titulo", "pedido", "aEstados"));
        }

        $titulo = "Lista de Pedidos";
        $msg["ESTADO"] = MSG_ERROR;
        $msg["MSG"] = "El pedido especificado no existe.";
        $aEstados = Estado::obtenerTodos();
        $aSucursales = Sucursal::obtenerTodos();
        $countPedidos = Pedido::contarRegistros();
        return view("sistema.pedido-listar", compact("titulo", "countPedidos", "msg", "aEstados", "aSucursales"));
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
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
                    return view("sistema.error", compact("titulo", "msg"));
                }

                $entidad->actualizar();

                $_POST["id"] = $entidad->idproducto;
                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = OKINSERT;
            } else {
                if (!Patente::autorizarOperacion($codigo = "PEDIDOALTA")) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
                    return view("sistema.error", compact("titulo", "msg"));
                }

                if (empty($entidad->fk_idcliente) || empty($entidad->fk_idsucursal) || empty($entidad->fk_idestado) || empty($entidad->fecha) || empty($entidad->total)) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "Ingrese todos los datos requeridos.";
                } else {
                    $entidad->insertar();

                    $_POST["id"] = $entidad->idproducto;
                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                }
            }

            $_POST["id"] = $entidad->idpedido;

            $msg["ESTADO"] = MSG_SUCCESS;
            $msg["MSG"] = OKINSERT;

            $aEstados = Estado::obtenerTodos();
            $aSucursales = Sucursal::obtenerTodos();
            $countPedidos = Pedido::contarRegistros();
            return view("sistema.pedido-listar", compact("titulo", "countPedidos", "msg", "aEstados", "aSucursales"));
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        if (!Patente::autorizarOperacion($codigo = "PEDIDOCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $pedido = Pedido::obtenerPorId($entidad->idpedido) ?? new Pedido(["idpedido"=>$entidad->idpedido]);
        $aClientes = Cliente::obtenerTodos();
        $aSucursales = Sucursal::obtenerTodos();
        $aEstados = Estado::obtenerTodos();
        return view("sistema.pedido-nuevo", compact("titulo", "msg", "pedido", "aClientes", "aSucursales", "aEstados"));
    }

    public function eliminar(Request $request)
    {
        if (!Usuario::autenticado()) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "Usuario no autenticado.";
            return json_encode($aResultado);
        }

        if (!Patente::autorizarOperacion($codigo = "PEDIDOBAJA")) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No tiene permisos para la operación ($codigo).";
            return json_encode($aResultado);
        }

        try {
            $pedido = new Pedido();
            $pedido->idpedido = $request->id;
            $pedido->eliminar();

            $aResultado["err"] = EXIT_SUCCESS;
            $aResultado["msg"] = "Pedido eliminado exitosamente.";
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No se pudo eliminar el pedido.";
        }

        return json_encode($aResultado);
    }

    public function setEstado(Request $request)
    {
        if (!Usuario::autenticado()) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "Usuario no autenticado.";
            return json_encode($aResultado);
        }

        if (!Patente::autorizarOperacion($codigo = "PEDIDOEDITAR")) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No tiene permisos para la operación ($codigo).";
            return json_encode($aResultado);
        }

        try {
            $pedido = Pedido::obtenerPorId($request->id);
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No se pudo encontrar el pedido.";
            return json_encode($aResultado);
        }

        try {
            $pedido->fk_idestado = $request->estado;
            $pedido->actualizar();
            $aResultado["err"] = EXIT_SUCCESS;
            $aResultado["msg"] = "Pedido editado exitosamente.";
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "Fallo al editar el estado del pedido.";
        }

        return json_encode($aResultado);
    }

    private function selectEstado($aEstados, Pedido $pedido)
    {
        $select = '<select id="lstEstado-id' . $pedido->idpedido . '" class="form-control" onchange="javascript: setEstado(' . $pedido->idpedido . ');">';
        foreach ($aEstados as $estado) {
            if ($estado->idestado == $pedido->fk_idestado) {
                $select .= '<option selected value="' . $estado->idestado .'">'. $estado->nombre . '</option>';
            } else {
                $select .= '<option value="' . $estado->idestado .'">'. $estado->nombre . '</option>';
            }
        }
        $select .= '</select>';

        return $select;
    }

    public function cargarGrilla(Request $request)
    {
        if (!Usuario::autenticado() || !Patente::autorizarOperacion("PEDIDOCONSULTA"))
            return null;

        $estado = $request->estado;
        $sucursal = $request->sucursal;
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $start = $request->start ?? 0;
        $length = $request->length ?? 25;

        $count = Pedido::contarRegistros($estado, $sucursal, $fechaDesde, $fechaHasta);
        $aSlice = Pedido::obtenerFiltrado($estado, $sucursal, $fechaDesde, $fechaHasta, $start, $length);

        $aEstados = Estado::obtenerTodos();

        $data = [];
        foreach ($aSlice as $pedido) {
            $row = [];
            $row[] = '<a href="/admin/pedido/' . $pedido->idpedido . '" class="btn btn-secondary">' . '<i class="fas fa-eye">' . '</a>';
            $row[] = $pedido->idpedido;
            $row[] = $pedido->cliente;
            $row[] = $pedido->sucursal;
            $row[] = $this->selectEstado($aEstados, $pedido);
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
