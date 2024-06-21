<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use App\Entidades\Estado;
use App\Entidades\Pedido;
use App\Entidades\Sucursal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require app_path() . '/start/constants.php';

class ControladorPedido extends Controller
{
    public function index()
    {
        $titulo = "Lista de Pedidos";

        $aEstados = Estado::withCount('pedidos')->get();
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

        Session::flash("msg", [
            "ESTADO" => MSG_ERROR,
            "MSG" => "El pedido especificado no existe"
        ]);
        return redirect('/admin/pedidos');
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Pedido";

        $pedido = Pedido::findOrNew($request->input('id'));

        $pedido->cargarDesdeRequest($request);

        if (empty($pedido->fk_idcliente) || empty($pedido->fk_idsucursal) || empty($pedido->fk_idestado) || empty($pedido->fecha) || empty($pedido->total)) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => "Ingrese todos los datos requeridos."
            ]);

            if ($pedido->exists) {
                $pedido->refresh();
            }

            $aEstados = Estado::all();
            return view("sistema.pedido-nuevo", compact("titulo", "pedido", "aEstados"));
        }

        try {
            $pedido->save();

            $_POST["id"] = $pedido->idpedido;
            Session::flash("msg", [
                "ESTADO" => MSG_SUCCESS,
                "MSG" => OKINSERT
            ]);

            return redirect('/admin/pedidos');
        } catch (Exception $e) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => ERRORINSERT
            ]);
        }

        if ($pedido->exists) {
            $pedido->refresh();
        }

        $aClientes = Cliente::all();
        $aSucursales = Sucursal::all();
        $aEstados = Estado::all();
        return view("sistema.pedido-nuevo", compact("titulo", "pedido", "aClientes", "aSucursales", "aEstados"));
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
            $pedido->save();
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
