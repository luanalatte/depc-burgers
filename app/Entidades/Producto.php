<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'idproducto';

    public $timestamps = false;

    protected $fillable = [
        'idproducto', 'fk_idcategoria', 'nombre', 'cantidad', 'precio', 'descripcion', 'imagen'
    ];

    public function scopeOrderByCategoria(Builder $query)
    {
        return $query->withoutGlobalScope('order')
            ->join('categorias', 'productos.fk_idcategoria', '=', 'categorias.idcategoria')
            ->orderBy('categorias.posicion', 'desc');
    }

    public function scopeGrilla(Builder $query, int $orderColumnIdx = 0, string $orderDirection = "asc")
    {
        $columnas = ['nombre', 'categoria', 'cantidad', 'precio', 'descripcion'];

        $orderColumn = $columnas[$orderColumnIdx] ?? 'nombre';
        $orderDirection = $orderDirection == 'desc' ? 'desc' : 'asc';

        return $query->withoutGlobalScope('order')
            ->join('categorias', 'productos.fk_idcategoria', '=', 'categorias.idcategoria')
            ->orderBy($orderColumn, $orderDirection)
            ->select(
                'productos.idproducto',
                'productos.nombre',
                'productos.cantidad',
                'productos.precio',
                'productos.descripcion',
                'productos.imagen',
                'categorias.nombre AS categoria'
            );
    }

    public function cargarDesdeRequest(Request $request)
    {
        $this->idproducto = $request->input('id') != "0" ? $request->input('id') : $this->idproducto;

        $this->fk_idcategoria = $request->input('lstCategoria');

        $this->nombre = trimIfString($request->input('txtNombre'));
        $this->cantidad = trimIfString($request->input('txtCantidad'));
        $this->precio = trimIfString($request->input('txtPrecio'));
        $this->descripcion = trimIfString($request->input('txtDescripcion'));
    }

    public function insertar() {
        $sql = "INSERT INTO productos (
                  fk_idcategoria,
                  nombre,
                  cantidad,
                  precio,
                  descripcion,
                  imagen
                ) VALUES (?, ?, ?, ?, ?, ?)";

        DB::insert($sql, [$this->fk_idcategoria, $this->nombre, $this->cantidad, $this->precio, $this->descripcion, $this->imagen]);
        $this->idproducto = DB::getPdo()->lastInsertId();

        return $this->idproducto;
    }

    public function actualizar() {
        $sql = "UPDATE productos SET
                  fk_idcategoria = ?,
                  nombre = ?,
                  cantidad = ?,
                  precio = ?,
                  descripcion = ?,
                  imagen = ?
                WHERE idproducto = ?";

        DB::update($sql, [
            $this->fk_idcategoria,
            $this->nombre,
            $this->cantidad,
            $this->precio,
            $this->descripcion,
            $this->imagen,
            $this->idproducto
        ]);
    }
}