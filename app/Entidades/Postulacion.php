<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Postulacion extends Model
{
    protected $table = 'postulaciones';
    protected $primaryKey = 'idpostulacion';

    public $timestamps = false;

    protected $fillable = [
        'idpostulacion', 'nombre', 'apellido', 'telefono', 'email', 'archivo'
    ];

    public function cargarDesdeRequest(Request $request)
    {
        $this->nombre = $request->input('txtNombre');
        $this->apellido = $request->input('txtApellido');
        $this->telefono = $request->input('txtTelefono');
        $this->email = $request->input('txtEmail');

        // TODO: Cargar archivo en ControladorPostulacion
    }
}