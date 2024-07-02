<?php

namespace App\Http\Controllers;

use App\Entidades\Sucursal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ControladorSucursal extends Controller
{
    public function index()
    {
        $titulo = "Lista de Sucursales";

        return view("sistema.sucursal-listar", compact("titulo"));
    }

    public function nuevo()
    {
        $titulo = "Nueva Sucursal";

        $sucursal = new Sucursal();
        return view("sistema.sucursal-nuevo", compact("titulo", "sucursal"));
    }

    public function editar(Request $request)
    {
        $titulo = "Modificar Sucursales";

        if ($sucursal = Sucursal::find($request->id)) {
            return view("sistema.sucursal-nuevo", compact("titulo", "sucursal"));
        }

        Session::flash("msg", [
            "ESTADO" => MSG_ERROR,
            "MSG" => "La sucursal especificada no existe."
        ]);

        return redirect("/admin/sucursales");
    }

    public function guardar(Request $request)
    {
        $titulo = "Modificar Sucursales";

        $sucursal = Sucursal::findOrNew($request->input('id'));
        $sucursal->cargarDesdeRequest($request);

        if (empty($sucursal->nombre) || empty($sucursal->direccion) || empty($sucursal->telefono)) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => "Ingrese todos los datos requeridos."
            ]);

            if ($sucursal->exists) {
                $sucursal->refresh();
            }

            return view("sistema.sucursal-nuevo", compact("titulo", "sucursal"));
        }

        try {
            $sucursal->save();

            $_POST["id"] = $sucursal->idsucursal;
            Session::flash("msg", [
                "ESTADO" => MSG_SUCCESS,
                "MSG" => OKINSERT
            ]);

            return redirect('/admin/sucursales');
        } catch (Exception $e) {
            Session::flash("msg", [
                "ESTADO" => MSG_ERROR,
                "MSG" => ERRORINSERT
            ]);
        }

        if ($sucursal->exists) {
            $sucursal->refresh();
        }

        return view("sistema.sucursal-nuevo", compact("titulo", "sucursal"));
    }

    public function eliminar(Request $request)
    {
        try {
            Sucursal::destroy($request->id);

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
