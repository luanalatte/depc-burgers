<?php

namespace App\Http\Controllers;

use App\Entidades\Categoria;
use App\Entidades\Sistema\Patente;
use App\Entidades\Sistema\Usuario;
use Exception;
use Illuminate\Http\Request;

class ControladorCategoria extends Controller
{
    public function index()
    {
        $titulo = "Lista de Categorías de Productos";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        return view("sistema.categoria-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nueva Categoría de Producto";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOSALTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $categoria = new Categoria();
        return view("sistema.categoria-nuevo", compact("titulo", "categoria"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Categoría de Productos";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        if ($categoria = Categoria::find($request->id)) {
            return view("sistema.categoria-nuevo", compact("titulo", "categoria"));
        }

        $titulo = "Lista de Categorías de Producto";
        $msg["ESTADO"] = MSG_ERROR;
        $msg["MSG"] = "La categoría de producto especificada no existe.";
        return view("sistema.categoria-listar", compact("titulo", "msg"));
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Categoría de Productos";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        $entidad = new Categoria();
        $entidad->cargarDesdeRequest($request);

        try {
            $bEditando = $_POST["id"] > 0;

            if (empty($entidad->nombre)) {
                $msg["ESTADO"] = MSG_ERROR;
                $msg["MSG"] = "Ingrese todos los datos requeridos.";
            } else {
                if ($bEditando) {
                    if (!Patente::autorizarOperacion($codigo = "PRODUCTOEDITAR")) {
                        $msg["ESTADO"] = MSG_ERROR;
                        $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
                        return view("sistema.error", compact("titulo", "msg"));
                    }

                    $entidad->actualizar();

                    $_POST["id"] = $entidad->idproducto;
                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                } else {
                    if (!Patente::autorizarOperacion($codigo = "PRODUCTOSALTA")) {
                        $msg["ESTADO"] = MSG_ERROR;
                        $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
                        return view("sistema.error", compact("titulo", "msg"));
                    }

                    $entidad->insertar();

                    $_POST["id"] = $entidad->idproducto;
                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                }

                return view("sistema.categoria-listar", compact("titulo", "msg"));
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

        $categoria = Categoria::find($entidad->idcategoria) ?? new Categoria();
        return view("sistema.categoria-nuevo", compact("titulo", "msg", "categoria"));
    }

    public function eliminar(Request $request)
    {
        if (!Usuario::autenticado()) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "Usuario no autenticado.";
            return json_encode($aResultado);
        }

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOELIMINAR")) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No tiene permisos para la operación ($codigo).";
            return json_encode($aResultado);
        }

        try {
            $categoria = new Categoria();
            $categoria->idcategoria = $request->id;
            $categoria->eliminar();

            $aResultado["err"] = EXIT_SUCCESS;
            $aResultado["msg"] = "Categoría de productos eliminada exitosamente.";
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No se pudo eliminar la categoría de productos.";
        }

        return json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
        if (!Usuario::autenticado() || !Patente::autorizarOperacion("PRODUCTOCONSULTA"))
            return null;

        // NOTE: Posible injection en los valores de DataTables?
        $orderColumn = $request->order[0]['column'];
        $orderDirection = $request->order[0]['dir'];
        $offset = $request->start ?? 0;
        $limit = $request->length ?? 25;

        $count = Categoria::count();
        $aSlice = Categoria::grilla($orderColumn, $orderDirection)->offset($offset)->limit($limit)->get();

        $data = [];
        foreach ($aSlice as $categoria) {
            $row = [
                '<a href="/admin/categoria/' . $categoria->idcategoria . '">' . $categoria->nombre . '</a>'
            ];
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
