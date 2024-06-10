<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Producto extends Model
{
    protected $table = 'productos';
    public $timestamps = false;

    protected $fillable = [
        'idproducto', 'fk_idcategoria', 'nombre', 'cantidad', 'precio', 'descripcion', 'imagen'
    ];

    public $categoria;

    // protected $hidden = [];

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

    public function eliminar() {
        $sql = "DELETE FROM productos WHERE idproducto = ?";
        DB::delete($sql, [$this->idproducto]);
    }

    private static function construirDesdeFila($fila) {
        if (!$fila)
            return null;

        $producto = new Producto();
        $producto->idproducto = $fila->idproducto;
        $producto->fk_idcategoria = $fila->fk_idcategoria;
        $producto->nombre = $fila->nombre;
        $producto->cantidad = $fila->cantidad;
        $producto->precio = $fila->precio;
        $producto->descripcion = $fila->descripcion;
        $producto->imagen = $fila->imagen;

        $producto->categoria = $fila->categoria ?? null;

        return $producto;
    }

    public static function obtenerPorId($idproducto)
    {
        $sql = "SELECT
                  idproducto,
                  fk_idcategoria,
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
                  fk_idcategoria,
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

    public static function contarRegistros()
    {
        // TODO: Incluir filtrado de obtenerPaginado
        $sql = "SELECT COUNT(*) AS total FROM productos";

        if ($fila = DB::selectOne($sql)) {
            return $fila->total;
        }

        return 0;
    }

    public static function obtenerPaginado(int $inicio = 0, int $cantidad = 25)
    {
        $sql = "SELECT
                  A.idproducto,
                  A.fk_idcategoria,
                  A.nombre,
                  A.cantidad,
                  A.precio,
                  A.descripcion,
                  A.imagen,
                  B.nombre AS categoria
                FROM productos A
                INNER JOIN categorias B ON A.fk_idcategoria = B.idcategoria
                ORDER BY A.idproducto LIMIT $inicio, $cantidad";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}