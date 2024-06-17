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

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOSALTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $producto = new Producto();
        $aCategorias = Categoria::all();
        return view("sistema.producto-nuevo", compact("titulo", "producto", "aCategorias"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Producto";

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        if ($producto = Producto::find($request->id)) {
            $aCategorias = Categoria::all();
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

        $entidad = new Producto();
        $entidad->cargarDesdeRequest($request);

        try {
            $imagen = uploadFile($_FILES["fileImagen"], ["jpg", "jpeg", "png", "webp", "gif"]);
            if (!is_null($imagen)) {
                $entidad->imagen = $imagen;
            }

            if ($_POST["id"] > 0) {
                if (!Patente::autorizarOperacion($codigo = "PRODUCTOEDITAR")) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
                    return view("sistema.error", compact("titulo", "msg"));
                }

                $productoAnt = Producto::find($entidad->idproducto);
                if (!is_null($imagen)) {
                    try {
                        unlink(env('APP_PATH') . "/public/files/$productoAnt->imagen");
                    } catch (Exception $e) {

                    }
                } else {
                    $entidad->imagen = $productoAnt->imagen;
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

                if (empty($entidad->nombre) || empty($entidad->precio)) {
                    $msg["ESTADO"] = MSG_ERROR;
                    $msg["MSG"] = "Ingrese todos los datos requeridos.";
                } else {
                    $entidad->insertar();

                    $_POST["id"] = $entidad->idproducto;
                    $msg["ESTADO"] = MSG_SUCCESS;
                    $msg["MSG"] = OKINSERT;
                }
            }

            return view("sistema.producto-listar", compact("titulo", "msg"));
        } catch (Exception $e) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = ERRORINSERT;
        }

        if (!Patente::autorizarOperacion($codigo = "PRODUCTOCONSULTA")) {
            $msg["ESTADO"] = MSG_ERROR;
            $msg["MSG"] = "No tiene permisos para la operación ($codigo).";
            return view("sistema.error", compact("titulo", "msg"));
        }

        $producto = Producto::find($entidad->idproducto) ?? new Producto();
        $aCategorias = Categoria::all();
        return view("sistema.producto-nuevo", compact("titulo", "msg", "producto", "aCategorias"));
    }

    public function eliminar(Request $request)
    {
        if (!Patente::autorizarOperacion($codigo = "PRODUCTOELIMINAR")) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No tiene permisos para la operación ($codigo).";
            return json_encode($aResultado);
        }

        try {
            // TODO: No eliminar productos que tengan pedidos asociados.
            Producto::destroy($request->id);

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
        if (!Patente::autorizarOperacion("PRODUCTOCONSULTA"))
            return null;

        // NOTE: Posible injection en los valores de DataTables?
        $orderColumn = $request->order[0]['column'];
        $orderDirection = $request->order[0]['dir'];
        $offset = $request->start ?? 0;
        $limit = $request->length ?? 25;

        $count = Producto::count();
        $aSlice = Producto::grilla($orderColumn, $orderDirection)->offset($offset)->limit($limit)->get();

        $data = [];
        foreach ($aSlice as $producto) {
            $row = [];
            $row[] = '<a href="/admin/producto/' . $producto->idproducto . '">' . $producto->nombre . '</a>';
            $row[] = $producto->categoria;
            $row[] = $producto->cantidad;
            $row[] = number_format($producto->precio, 2, ',', ".");
            $row[] = $producto->descripcion;

            if ($producto->imagen) {
                $row[] = '<img src="/files/'. $producto->imagen .'" class="img-fluid">';
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
