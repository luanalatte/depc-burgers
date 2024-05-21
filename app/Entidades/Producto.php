<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    public $timestamps = false;

    protected $fillable = [
        'idproducto', 'nombre', 'cantidad', 'precio', 'descripcion', 'imagen'
    ];

    // protected $hidden = [];

    public function insertar() {
        $sql = "INSERT INTO productos (
                  nombre,
                  cantidad,
                  precio,
                  descripcion,
                  imagen
                ) VALUES (?, ?, ?, ?, ?)";

        DB::insert($sql, [$this->nombre, $this->cantidad, $this->precio, $this->descripcion, $this->imagen]);
        $this->idproducto = DB::getPdo()->lastInsertId();

        return $this->idproducto;
    }

    public function actualizar() {
        $sql = "UPDATE productos SET
                  nombre = ?,
                  cantidad = ?,
                  precio = ?,
                  descripcion = ?,
                  imagen = ?
                WHERE idproducto = ?";

        DB::update($sql, [
            $this->nombre,
            $this->cantidad,
            $this->precio,
            $this->descripcion,
            $this->imagen,
            $this->idproducto
        ]);
    }

    public function eliminar() {
        $sql = "DELETE FROM productos WHERE idproducto = ?";
        DB::delete($sql, [$this->idproducto]);
    }

    private static function construirDesdeFila($fila) {
        if (!$fila)
            return null;

        $producto = new Producto();
        $producto->idproducto = $fila->idproducto;
        $producto->nombre = $fila->nombre;
        $producto->cantidad = $fila->cantidad;
        $producto->precio = $fila->precio;
        $producto->descripcion = $fila->descripcion;
        $producto->imagen = $fila->imagen;

        return $producto;
    }

    public static function obtenerPorId($idproducto)
    {
        $sql = "SELECT
                  idproducto,
                  nombre,
                  cantidad,
                  precio,
                  descripcion,
                  imagen
                FROM productos WHERE idproducto = ?";

        return self::construirDesdeFila(DB::selectOne($sql, [$idproducto]));
    }

    public static function obtenerTodos()
    {
        $sql = "SELECT
                  idproducto,
                  nombre,
                  cantidad,
                  precio,
                  descripcion,
                  imagen
                FROM productos ORDER BY nombre";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}