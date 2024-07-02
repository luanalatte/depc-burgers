<?php

namespace App\Entidades;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Postulacion extends Model
{
    protected $table = 'postulaciones';
    protected $primaryKey = 'idpostulacion';

    public $timestamps = false;

    protected $fillable = [
        'idpostulacion', 'nombre', 'apellido', 'domicilio', 'telefono', 'email', 'archivo'
    ];

    protected static function booted()
    {
        static::deleting(function ($postulacion) {
            if ($postulacion->archivo) {
                try {
                    Storage::delete('cv/' . $postulacion->archivo);
                } catch (Exception $e) {}
            }
        });
    }

    public function scopeGrilla(Builder $query, int $orderColumnIdx = 0, string $orderDirection = "desc")
    {
        $columnas = ['idpostulacion', 'nombre', 'apellido', 'email', 'telefono', 'domicilio'];

        $orderColumn = $columnas[$orderColumnIdx] ?? 'idpostulacion';
        $orderDirection = $orderDirection == 'asc' ? 'asc' : 'desc';

        return $query->withoutGlobalScope('order')
            ->orderBy($orderColumn, $orderDirection)
            ->select('idpostulacion', 'nombre', 'apellido', 'email', 'telefono', 'domicilio', 'archivo');
    }

    public function cargarDesdeRequest(Request $request)
    {
        $this->nombre = $request->input('txtNombre');
        $this->apellido = $request->input('txtApellido');
        $this->domicilio = $request->input('txtDomicilio');
        $this->telefono = $request->input('txtTelefono');
        $this->email = $request->input('txtEmail');
    }
}