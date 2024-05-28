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
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        return view("sistema.sucursal-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nueva Sucursal";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "SUCURSALALTA")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
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
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        if ($sucursal = Sucursal::obtenerPorId($request->id)) {
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
                        $mensaje = "No tiene pemisos para la operación.";
                        return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
                    }

                    $entidad->actualizar();
                } else {
                    if (!Patente::autorizarOperacion($codigo = "SUCURSALALTA")) {
                        $mensaje = "No tiene pemisos para la operación.";
                        return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
                    }

                    $entidad->insertar();
                }

                $_POST["id"] = $entidad->idsucursal;

                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = OKINSERT;

                return view("sistema.sucursal-listar", compact("titulo", "msg"));
            }
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOSCONSULTA")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        $sucursal = Sucursal::obtenerPorId($entidad->idsucursal) ?? new Sucursal(["idsucursal"=>$entidad->idsucursal]);
        return view("sistema.sucursal-nuevo", compact("titulo", "msg", "sucursal"));
    }

    public function eliminar(Request $request)
    {
        $titulo = "Eliminar Sucursales";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "SUCURSALBAJA")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        try {
            $sucursal = new Sucursal();
            $sucursal->idsucursal = $request->id;
            $sucursal->eliminar();

            $aResultado["err"] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $aResultado["err"] = "Error en la operación. No se pudo eliminar la sucursales.";
        }

        echo json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
        if (!Usuario::autenticado() || !Patente::autorizarOperacion("SUCURSALCONSULTA"))
            return null;

        $count = Sucursal::contarRegistros();
        $aSlice = Sucursal::obtenerPaginado($request->start ?? 0, $request->length ?? 25);

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
