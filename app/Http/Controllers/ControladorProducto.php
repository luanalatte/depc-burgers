<?php

namespace App\Http\Controllers;

use App\Entidades\Categoria;
use App\Entidades\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require app_path() . '/start/constants.php';

class ControladorProducto extends Controller
{
    public function index()
    {
        $titulo = "Lista de Productos";

        return view("sistema.producto-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nuevo Producto";

        $producto = new Producto();
        $aCategorias = Categoria::all();
        return view("sistema.producto-nuevo", compact("titulo", "producto", "aCategorias"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Producto";

        if ($producto = Producto::find($request->id)) {
            $aCategorias = Categoria::all();
            return view("sistema.producto-nuevo", compact("titulo", "producto", "aCategorias"));
        }

        Session::flash("msg", [
            "ESTADO" => MSG_ERROR,
            "MSG" => "El producto especificado no existe."
        ]);

        return redirect("/admin/productos");
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Producto";

        $producto = Producto::findOrNew($request->input('id'));

        $producto->cargarDesdeRequest($request);

        if (empty($producto->nombre) || empty($producto->precio)) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => "Ingrese todos los datos requeridos."
            ]);

            if ($producto->exists) {
                $producto->refresh();
            }

            return view("sistema.producto-nuevo", compact("titulo", "producto"));
        }

        $imagen = uploadFile($_FILES["fileImagen"], ["jpg", "jpeg", "png", "webp", "gif"]);
        if (!is_null($imagen)) {
            if ($producto->imagen) {
                unlink(env('APP_PATH') . "/public/files/$producto->imagen");
            }

            $producto->imagen = $imagen;
        }

        try {
            $producto->save();

            $_POST["id"] = $producto->idproducto;
            Session::flash("msg", [
                "ESTADO" => MSG_SUCCESS,
                "MSG" => OKINSERT
            ]);

            return redirect("/admin/productos");
        } catch (Exception $e) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => ERRORINSERT
            ]);
        }

        if ($producto->exists) {
            $producto->refresh();
        }

        $aCategorias = Categoria::all();
        return view("sistema.producto-nuevo", compact("titulo", "producto", "aCategorias"));
    }

    public function eliminar(Request $request)
    {
        try {
            Producto::destroy($request->id);

            $aResultado["err"] = EXIT_SUCCESS;
            $aResultado["msg"] = "Producto eliminado exitosamente.";
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No se pudo eliminar el producto. Es posible que tenga pedidos asociados.";
        }

        return json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
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
