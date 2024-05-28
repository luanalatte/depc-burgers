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
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        return view("sistema.categoria-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nueva Categoría de Producto";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOSALTA")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
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
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        if ($categoria = Categoria::obtenerPorId($request->id)) {
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
                        $mensaje = "No tiene pemisos para la operación.";
                        return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
                    }

                    $entidad->actualizar();
                } else {
                    if (!Patente::autorizarOperacion($codigo = "PRODUCTOSALTA")) {
                        $mensaje = "No tiene pemisos para la operación.";
                        return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
                    }

                    $entidad->insertar();
                }

                $_POST["id"] = $entidad->idcategoria;

                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = OKINSERT;

                return view("sistema.categoria-listar", compact("titulo", "msg"));
            }
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOSCONSULTA")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
        }

        $categoria = Categoria::obtenerPorId($entidad->idcategoria) ?? new Categoria(["idcategoria"=>$entidad->idcategoria]);
        return view("sistema.categoria-nuevo", compact("titulo", "msg", "categoria"));
    }

    public function eliminar(Request $request)
    {
        $titulo = "Eliminar Categoría de Productos";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOELIMINAR")) {
            $mensaje = "No tiene pemisos para la operación.";
            return view("sistema.pagina-error", compact("titulo", "codigo", "mensaje"));
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

        echo json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
        if (!Usuario::autenticado() || !Patente::autorizarOperacion("PRODUCTOCONSULTA"))
            return null;

        $count = Categoria::contarRegistros();
        $aSlice = Categoria::obtenerPaginado($request->start ?? 0, $request->length ?? 25);

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
