<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';
    protected $primaryKey = 'idcarrito';

    public $timestamps = false;

    protected $fillable = [
        'idcarrito', 'fk_idcliente'
    ];

    public $total = 0;
    public $aProductos = [];

    protected static function booted() {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('idcarrito', 'desc');
        });
    }

    public function insertar() {
        $sql = "INSERT INTO carritos (
                  fk_idcliente
                ) VALUES (?)";

        DB::insert($sql, [$this->fk_idcliente]);
        $this->idcarrito = DB::getPdo()->lastInsertId();

        return $this->idcarrito;
    }

    public function actualizar() {
        $sql = "UPDATE carritos SET
                  fk_idcliente = ?
                WHERE idcarrito = ?";

        DB::update($sql, [
            $this->fk_idcliente,
            $this->idcarrito
        ]);
    }

    public function obtenerCantidad($idproducto)
    {
        $sql = "SELECT cantidad FROM carrito_productos WHERE fk_idcarrito = ? AND fk_idproducto = ?";

        return DB::selectOne($sql, [$this->idcarrito, $idproducto])->cantidad ?? 0;
    }

    public function agregarProducto($idproducto, int $cantidad = 1)
    {
        if ($cantidad <= 0) {
            return;
        }

        $cantidadActual = $this->obtenerCantidad($idproducto);
        if ($cantidadActual == 0) {
            $sql = "INSERT INTO carrito_productos (
                      fk_idcarrito,
                      fk_idproducto,
                      cantidad
                    ) VALUES (?, ?, ?)";
    
            DB::insert($sql, [$this->idcarrito, $idproducto, $cantidad]);
        } else {
            $this->editarProducto($idproducto, $cantidadActual + $cantidad);
        }
    }

    public function editarProducto($idproducto, int $cantidad)
    {
        if ($cantidad <= 0) {
            return $this->eliminarProducto($idproducto);
        }

        $sql = "UPDATE carrito_productos SET
                  cantidad = ?
                WHERE fk_idcarrito = ? AND fk_idproducto = ?";

        DB::update($sql, [$cantidad, $this->idcarrito, $idproducto]);
    }

    public function eliminarProducto($idproducto)
    {
        $sql = "DELETE FROM carrito_productos WHERE fk_idcarrito = ? AND fk_idproducto = ?";

        DB::delete($sql, [$this->idcarrito, $idproducto]);
    }

    private static function construirDesdeFila($fila) {
        if (!$fila)
            return null;

        $carrito = new Carrito();
        $carrito->idcarrito = $fila->idcarrito;
        $carrito->fk_idcliente = $fila->fk_idcliente;

        $carrito->total = $fila->total ?? 0;

        return $carrito;
    }

    public static function cargarCompleto($idcliente)
    {
        $carrito = self::where('fk_idcliente', $idcliente)->first();
        if ($carrito == null) {
            return null;
        }

        $sql = "SELECT
                  A.idcarrito,
                  A.fk_idcliente,
                  C.idproducto,
                  C.nombre AS prod_nombre,
                  C.cantidad AS prod_cantidad,
                  C.precio AS prod_precio,
                  C.descripcion AS prod_descripcion,
                  C.imagen AS prod_imagen,
                  B.cantidad,
                  @Subtotal := C.precio * B.cantidad AS subtotal
                FROM carritos A
                LEFT JOIN carrito_productos B ON A.idcarrito = B.fk_idcarrito
                LEFT JOIN productos C ON B.fk_idproducto = C.idproducto
                WHERE A.idcarrito = ?";

        $aFilas = DB::select($sql, [$carrito->idcarrito]);
        if (!$aFilas) {
            return null;
        }

        $carrito = self::construirDesdeFila($aFilas[0]);

        foreach ($aFilas as $fila) {
            $carrito->aProductos[] = [
                'producto' => new Producto([
                    'idproducto' => $fila->idproducto,
                    'nombre' => $fila->prod_nombre,
                    'cantidad' => $fila->prod_cantidad,
                    'precio' => $fila->prod_precio,
                    'descripcion' => $fila->prod_descripcion,
                    'imagen' => $fila->prod_imagen
                ]),
                'cantidad' => $fila->cantidad,
                'subtotal' => $fila->subtotal
            ];

            $carrito->total += $fila->subtotal;
        }

        return $carrito;
    }
}