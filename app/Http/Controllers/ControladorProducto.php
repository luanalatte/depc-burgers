<?php

namespace App\Http\Controllers;

use App\Entidades\Categoria;
use App\Entidades\Producto;
use App\Entidades\Sistema\Usuario;
use App\Entidades\Sistema\Patente;
use Exception;
use Illuminate\Http\Request;

require app_path() . '/start/constants.php';

class ControladorProducto extends Controller
{
    public function index()
    {
        $titulo = "Lista de Productos";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        return view("sistema.producto-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nuevo Producto";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOSALTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $producto = new Producto();
        $aCategorias = Categoria::obtenerTodos();
        return view("sistema.producto-nuevo", compact("titulo", "producto", "aCategorias"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Producto";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        if ($producto = Producto::obtenerPorId($request->id)) {
            $aCategorias = Categoria::obtenerTodos();
            return view("sistema.producto-nuevo", compact("titulo", "producto", "aCategorias"));
        }

        $titulo = "Lista de Productos";
        $msg["ESTADO"] = MSG_ERROR;
        $msg["MSG"] = "El producto especificado no existe.";
        return view("sistema.producto-listar", compact("titulo", "msg"));
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Producto";

        if (!Usuario::autenticado())
            return redirect("admin/login");

        $entidad = new Producto();
        $entidad->cargarDesdeRequest($request);

        try {
            $bEditando = $_POST["id"] > 0;

            if (empty($entidad->nombre) || empty($entidad->precio)) {
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
                } else {
                    if (!Patente::autorizarOperacion($codigo = "PRODUCTOSALTA")) {
                        $msg["ESTADO"] = MSG_ERROR;
                        $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
                        return view("sistema.error", compact("titulo", "msg"));
                    }

                    $entidad->insertar();
                }

                $_POST["id"] = $entidad->idproducto;

                $msg["ESTADO"] = MSG_SUCCESS;
                $msg["MSG"] = OKINSERT;

                return view("sistema.producto-listar", compact("titulo", "msg"));
            }
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $producto = Producto::obtenerPorId($entidad->idproducto) ?? new Producto(["idproducto"=>$entidad->idproducto]);
        $aCategorias = Categoria::obtenerTodos();
        return view("sistema.producto-nuevo", compact("titulo", "msg", "producto", "aCategorias"));
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
            // TODO: No eliminar productos que tengan pedidos asociados.
            $producto = new Producto();
            $producto->idproducto = $request->id;
            $producto->eliminar();

            $aResultado["err"] = EXIT_SUCCESS;
            $aResultadp["msg"] = "Producto eliminado exitosamente.";
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No se pudo eliminar el producto.";
        }

        return json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
        if (!Usuario::autenticado() || !Patente::autorizarOperacion("PRODUCTOCONSULTA"))
            return null;

        $count = Producto::contarRegistros();
        $aSlice = Producto::obtenerPaginado($request->start ?? 0, $request->length ?? 25);

        $data = [];
        foreach ($aSlice as $producto) {
            $row = [];
            $row[] = '<a href="/admin/producto/' . $producto->idproducto . '">' . $producto->nombre . '</a>';
            $row[] = $producto->categoria;
            $row[] = $producto->cantidad;
            $row[] = number_format($producto->precio, 2, ',', ".");
            $row[] = $producto->descripcion;

            if ($producto->imagen) {
                $row[] = '<img src="$producto->imagen" class="img-fluid">';
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
