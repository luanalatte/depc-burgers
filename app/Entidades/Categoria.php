<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'idcategoria';

    public $timestamps = false;

    protected $fillable = [
        'idcategoria', 'nombre'
    ];

    protected static function booted() {
        static::addGlobalScope('order', function (Builder $builder) {
            // TODO: Añadir columna 'orden' para personalizar el orden de las columnas en la tienda.
            $builder->orderBy('nombre');
        });
    }

    public function scopeGrilla(Builder $query, int $orderColumnIdx = 0, string $orderDirection = "asc")
    {
        $columnas = ['nombre'];

        $orderColumn = $columnas[$orderColumnIdx] ?? 'nombre';
        $orderDirection = $orderDirection == 'desc' ? 'desc' : 'asc';

        return $query->withoutGlobalScope('order')
            ->orderBy($orderColumn, $orderDirection)
            ->select('idcategoria', 'nombre');
    }

    public function cargarDesdeRequest(Request $request)
    {
        $this->idcategoria = $request->input('id') != "0" ? $request->input('id') : $this->idcategoria;

        $this->nombre = trimIfString($request->input('txtNombre'));
    }

    public function insertar() {
        $sql = "INSERT INTO categorias (
                  nombre
                ) VALUES (?)";

        DB::insert($sql, [$this->nombre]);
        $this->idcategoria = DB::getPdo()->lastInsertId();

        return $this->idcategoria;
    }

    public function actualizar() {
        $sql = "UPDATE categorias SET
                  nombre = ?
                WHERE idcategoria = ?";

        DB::update($sql, [$this->nombre, $this->idcategoria]);
    }

    public function eliminar() {
        $sql = "DELETE FROM categorias WHERE idcategoria = ?";
        DB::delete($sql, [$this->idcategoria]);
    }
}