<?php

namespace App\Http\Controllers;

use App\Entidades\Cliente;
use App\Entidades\Sistema\Usuario;
use App\Entidades\Sistema\Patente;
use Exception;
use Illuminate\Http\Request;

require app_path() . '/start/constants.php';

class ControladorCliente extends Controller
{
    public function index()
    {
        $titulo = "Lista de Clientes";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "CLIENTECONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        return view("sistema.cliente-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nuevo Cliente";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "CLIENTEALTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $cliente = new Cliente();
        return view("sistema.cliente-nuevo", compact("titulo", "cliente"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Cliente";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "CLIENTECONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

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

        if (!Usuario::autenticado())
            return redirect("admin/login");

        $entidad = new Cliente();
        $entidad->cargarDesdeRequest($request);

        try {
            if ($_POST["id"] > 0) {
                if (!Patente::autorizarOperacion($codigo = "CLIENTEEDITAR")) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
                    return view("sistema.error", compact("titulo", "msg"));
                }

                $entidad->actualizar();

                $_POST["id"] = $entidad->idproducto;
                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = OKINSERT;
            } else {
                if (!Patente::autorizarOperacion($codigo = "CLIENTEALTA")) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
                    return view("sistema.error", compact("titulo", "msg"));
                }

                if (empty($entidad->nombre) || empty($entidad->apellido) || empty($entidad->dni) || empty($entidad->email) || empty($entidad->clave)) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "Ingrese todos los datos requeridos.";
                } else {
                    $entidad->insertar();

                    $_POST["id"] = $entidad->idproducto;
                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                }
            }

            return view("sistema.cliente-listar", compact("titulo", "msg"));
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        if (!Patente::autorizarOperacion($codigo = "CLIENTECONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $cliente = Cliente::find($entidad->idcliente) ?? new Cliente();
        return view("sistema.cliente-nuevo", compact("titulo", "msg", "cliente"));
    }

    public function eliminar(Request $request)
    {
        if (!Usuario::autenticado()) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "Usuario no autenticado.";
            return json_encode($aResultado);
        }

        if (!Patente::autorizarOperacion($codigo = "CLIENTEELIMINAR")) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No tiene permisos para la operación ($codigo).";
            return json_encode($aResultado);
        }

        try {
            $cliente = new Cliente();
            $cliente->idcliente = $request->id;
            $cliente->eliminar();

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
        if (!Usuario::autenticado() || !Patente::autorizarOperacion("CLIENTECONSULTA"))
            return null;

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
