<?php

namespace App\Http\Controllers;

use App\Entidades\Sucursal;
use App\Entidades\Sistema\Patente;
use App\Entidades\Sistema\Usuario;
use Exception;
use Illuminate\Http\Request;

class ControladorSucursal extends Controller
{
    public function index()
    {
        $titulo = "Lista de Sucursales";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "SUCURSALCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        return view("sistema.sucursal-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nueva Sucursal";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "SUCURSALALTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $sucursal = new Sucursal();
        return view("sistema.sucursal-nuevo", compact("titulo", "sucursal"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Sucursales";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "SUCURSALCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        if ($sucursal = Sucursal::find($request->id)) {
            return view("sistema.sucursal-nuevo", compact("titulo", "sucursal"));
        }

        $titulo = "Lista de Sucursales";
        $msg["ESTADO"] = MSG_ERROR;
        $msg["MSG"] = "La sucursal especificada no existe.";
        return view("sistema.sucursal-listar", compact("titulo", "msg"));
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Sucursales";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        $entidad = new Sucursal();
        $entidad->cargarDesdeRequest($request);

        try {
            $bEditando = $_POST["id"] > 0;

            if (empty($entidad->nombre) || empty($entidad->direccion) || empty($entidad->telefono)) {
                $msg["ESTADO"] = MSG_ERROR;
                $msg["MSG"] = "Ingrese todos los datos requeridos.";
            } else {
                if ($bEditando) {
                    if (!Patente::autorizarOperacion($codigo = "SUCURSALEDITAR")) {
                        $msg["ESTADO"] = MSG_ERROR;
                        $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
                        return view("sistema.error", compact("titulo", "msg"));
                    }

                    $entidad->actualizar();

                    $_POST["id"] = $entidad->idproducto;
                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                } else {
                    if (!Patente::autorizarOperacion($codigo = "SUCURSALALTA")) {
                        $msg["ESTADO"] = MSG_ERROR;
                        $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
                        return view("sistema.error", compact("titulo", "msg"));
                    }

                    $entidad->insertar();
                    
                    $_POST["id"] = $entidad->idproducto;
                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                }

                return view("sistema.sucursal-listar", compact("titulo", "msg"));
            }
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOSCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $sucursal = Sucursal::find($entidad->idsucursal) ?? new Sucursal();
        return view("sistema.sucursal-nuevo", compact("titulo", "msg", "sucursal"));
    }

    public function eliminar(Request $request)
    {
        if (!Usuario::autenticado()) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "Usuario no autenticado.";
            return json_encode($aResultado);
        }

        if (!Patente::autorizarOperacion($codigo = "SUCURSALBAJA")) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No tiene permisos para la operación ($codigo).";
            return json_encode($aResultado);
        }

        try {
            $sucursal = new Sucursal();
            $sucursal->idsucursal = $request->id;
            $sucursal->eliminar();

            $aResultado["err"] = EXIT_SUCCESS;
            $aResultado["msg"] = "Sucursal eliminada exitosamente.";
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No se pudo eliminar la sucursal.";
        }

        return json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
        if (!Usuario::autenticado() || !Patente::autorizarOperacion("SUCURSALCONSULTA"))
            return null;

        // NOTE: Posible injection en los valores de DataTables?
        $orderColumn = $request->order[0]['column'];
        $orderDirection = $request->order[0]['dir'];
        $offset = $request->start ?? 0;
        $limit = $request->length ?? 25;

        $count = Sucursal::count();
        $aSlice = Sucursal::grilla($orderColumn, $orderDirection)->offset($offset)->limit($limit)->get();

        $data = [];
        foreach ($aSlice as $sucursal) {
            $row = [];
            $row[] = '<a href="/admin/sucursal/' . $sucursal->idsucursal . '">' . $sucursal->nombre . '</a>';
            $row[] = $sucursal->direccion;
            $row[] = $sucursal->telefono;

            if ($sucursal->maps_url) {
                $row[] = '<a target="_blank" href="' . $sucursal->maps_url . '">Google Maps</a>';
            } else {
                $row[] = '';
            }

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
