<?php

namespace App\Http\Controllers;

use App\Entidades\Postulacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

require app_path() . '/start/constants.php';

class ControladorPostulacion extends Controller
{
    public function index()
    {
        return view("sistema.postulacion-listar");
    }

    public function descargar(Request $request)
    {
        $postulacion = Postulacion::select('archivo')->find($request->id);

        try {
            return Storage::download('cv/' . $postulacion->archivo);
        } catch (Exception $e) {
            // TODO: Llamar con ajax, devolver error.
        }
    }

    public function eliminar(Request $request)
    {
        try {
            Postulacion::destroy($request->id);

            $aResultado["err"] = EXIT_SUCCESS;
            $aResultado["msg"] = "postulación eliminada exitosamente.";
        } catch (Exception $e) {
            $aResultado["err"] = EXIT_FAILURE;
            $aResultado["msg"] = "No se pudo eliminar la postulación.";
        }

        return json_encode($aResultado);
    }

    public function cargarGrilla(Request $request)
    {
        try {
            $orderColumn = $request->order[0]['column'] - 1;
        } catch (Exception $e) {}

        try {
            $orderDirection = $request->order[0]['dir'];
        } catch (Exception $e) {}

        $offset = $request->start ?? 0;
        $limit = $request->length ?? 25;

        $aSlice = Postulacion::grilla($orderColumn ?? null, $orderDirection ?? null);

        $count = $aSlice->count();
        $aSlice = $aSlice->offset($offset)->limit($limit)->get();

        $data = [];
        foreach ($aSlice as $postulacion) {
            $row = [];
            $row[] = $postulacion->idpostulacion;
            $row[] = $postulacion->nombre;
            $row[] = $postulacion->apellido;
            $row[] = $postulacion->email;
            $row[] = $postulacion->telefono;
            $row[] = $postulacion->domicilio;
            $row[] = '<a href="/admin/postulacion/descargar/' . $postulacion->idpostulacion . '" class="btn btn-secondary">' . '<i class="fas fa-download">' . '</a>';
            // TODO: Ajax, eliminar y devolver notificación.
            $row[] = '<a href="/admin/postulacion/eliminar/' . $postulacion->idpostulacion . '" class="btn btn-secondary">' . '<i class="fas fa-trash">' . '</a>';
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
