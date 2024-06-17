<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use Exception;
use Illuminate\Http\Request;

require app_path() . '/start/constants.php';

class ControladorCliente extends Controller
{
    public function index()
    {
        $titulo = "Lista de Clientes";

        return view("sistema.cliente-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nuevo Cliente";

        $cliente = new Cliente();
        return view("sistema.cliente-nuevo", compact("titulo", "cliente"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Cliente";

        if ($cliente = Cliente::find($request->id)) {
            return view("sistema.cliente-nuevo", compact("titulo", "cliente"));
        }

        $titulo = "Lista de Clientes";
        $msg["ESTADO"] = MSG_ERROR;
        $msg["MSG"] = "El cliente especificado no existe.";
        return view("sistema.cliente-listar", compact("titulo", "msg"));
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Cliente";

        $entidad = new Cliente();
        $entidad->cargarDesdeRequest($request);

        try {
            if ($_POST["id"] > 0) {
                $entidad->actualizar();

                $_POST["id"] = $entidad->idcliente;
                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = OKINSERT;
            } else {
                if (empty($entidad->nombre) || empty($entidad->apellido) || empty($entidad->dni) || empty($entidad->email) || empty($entidad->clave)) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "Ingrese todos los datos requeridos.";
                } else {
                    $entidad->insertar();

                    $_POST["id"] = $entidad->idcliente;
                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                }
            }

            return view("sistema.cliente-listar", compact("titulo", "msg"));
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        $cliente = Cliente::find($entidad->idcliente) ?? new Cliente();
        return view("sistema.cliente-nuevo", compact("titulo", "msg", "cliente"));
    }

    public function eliminar(Request $request)
    {
        try {
            Cliente::destroy($request->id);

            $aResultado["err"] = EXIT_SUCCESS;
            $aResultado["msg"] = "Cliente eliminado exitosamente.";
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No se pudo eliminar el cliente.";
        }

        return json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
        // NOTE: Posible injection en los valores de DataTables?
        $orderColumn = $request->order[0]['column'];
        $orderDirection = $request->order[0]['dir'];
        $offset = $request->start ?? 0;
        $limit = $request->length ?? 25;

        $count = Cliente::count();
        $aSlice = Cliente::grilla($orderColumn, $orderDirection)->offset($offset)->limit($limit)->get();

        $data = [];
        foreach ($aSlice as $cliente) {
            $row = [];
            $row[] = '<a href="/admin/cliente/' . $cliente->idcliente . '">' . $cliente->nombre . '</a>';
            $row[] = $cliente->apellido;
            $row[] = $cliente->dni;
            $row[] = $cliente->email;
            $row[] = $cliente->telefono;
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
