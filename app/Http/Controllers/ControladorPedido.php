<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use App\Entidades\Estado;
use App\Entidades\Pedido;
use App\Entidades\Sucursal;
use Exception;
use Illuminate\Http\Request;

require app_path() . '/start/constants.php';

class ControladorPedido extends Controller
{
    public function index()
    {
        $titulo = "Lista de Pedidos";

        // TODO: Cambiar a Estado::withCount('pedidos')
        $aEstados = Estado::countPedidos()->get();
        $aSucursales = Sucursal::all();
        // TODO: Contar solo pedidos de la sucursal y/o período seleccionado actualmente.
        $countPedidos = Pedido::count();

        return view("sistema.pedido-listar", compact("titulo", "countPedidos", "aEstados", "aSucursales"));
    }

    public function nuevo()
    {
        $titulo = "Nuevo Pedido";

        $pedido = new Pedido();
        $aClientes = Cliente::all();
        $aSucursales = Sucursal::all();
        $aEstados = Estado::all();
        return view("sistema.pedido-nuevo", compact("titulo", "pedido", "aClientes", "aSucursales", "aEstados"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Pedido";

        $pedido = Pedido::incluirCliente()->incluirSucursal()->find($request->id);
        if ($pedido) {
            $aEstados = Estado::all();
            return view("sistema.pedido-nuevo", compact("titulo", "pedido", "aEstados"));
        }

        $titulo = "Lista de Pedidos";
        $msg["ESTADO"] = MSG_ERROR;
        $msg["MSG"] = "El pedido especificado no existe.";

        $aEstados = Estado::countPedidos()->get();
        $aSucursales = Sucursal::all();
        $countPedidos = Pedido::count();
        return view("sistema.pedido-listar", compact("titulo", "countPedidos", "msg", "aEstados", "aSucursales"));
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Pedido";

        $entidad = new Pedido();
        $entidad->cargarDesdeRequest($request);

        try {
            if ($_POST["id"] > 0) {
                $entidad->actualizar();

                $_POST["id"] = $entidad->idpedido;
                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = OKINSERT;
            } else {
                if (empty($entidad->fk_idcliente) || empty($entidad->fk_idsucursal) || empty($entidad->fk_idestado) || empty($entidad->fecha) || empty($entidad->total)) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "Ingrese todos los datos requeridos.";
                } else {
                    $entidad->insertar();

                    $_POST["id"] = $entidad->idpedido;
                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                }
            }

            $_POST["id"] = $entidad->idpedido;

            $msg["ESTADO"] = MSG_SUCCESS;
            $msg["MSG"] = OKINSERT;

            $aEstados = Estado::countPedidos()->get();
            $aSucursales = Sucursal::all();
            $countPedidos = Pedido::count();
            return view("sistema.pedido-listar", compact("titulo", "countPedidos", "msg", "aEstados", "aSucursales"));
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        $pedido = Pedido::find($entidad->idpedido) ?? new Pedido();
        $aClientes = Cliente::all();
        $aSucursales = Sucursal::all();
        $aEstados = Estado::all();
        return view("sistema.pedido-nuevo", compact("titulo", "msg", "pedido", "aClientes", "aSucursales", "aEstados"));
    }

    public function eliminar(Request $request)
    {
        try {
            Pedido::destroy($request->id);

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
        $pedido = Pedido::find($request->id);
        if ($pedido == null) {
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
        // NOTE: Posible injection en los valores de DataTables?
        try {
            $orderColumn = $request->order[0]['column'] - 1;
        } catch (Exception $e) {}

        try {
            $orderDirection = $request->order[0]['dir'];
        } catch (Exception $e) {}

        $offset = $request->start ?? 0;
        $limit = $request->length ?? 25;

        $aSlice = Pedido::grilla($orderColumn ?? null, $orderDirection ?? null)
            ->filtrarPeriodo($request->fechaDesde, $request->fechaHasta)
            ->filtrarEstado($request->estado)
            ->filtrarSucursal($request->sucursal);

        $count = $aSlice->count();
        $aSlice = $aSlice->offset($offset)->limit($limit)->get();

        $aEstados = Estado::all();

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
