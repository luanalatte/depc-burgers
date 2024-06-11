<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';
    public $timestamps = false;

    protected $fillable = [
        'idcarrito', 'fk_idcliente'
    ];

    public $total = 0;
    public $aProductos = [];

    // protected $hidden = [];

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

    public function eliminar() {
        $sql = "DELETE FROM carritos WHERE idcarrito = ?";
        DB::delete($sql, [$this->idcarrito]);
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

    public static function obtenerPorId($idcarrito)
    {
        $sql = "SELECT
                  idcarrito,
                  fk_idcliente
                FROM carritos WHERE idcarrito = ?";

        return self::construirDesdeFila(DB::selectOne($sql, [$idcarrito]));
    }

    public static function obtenerTodos()
    {
        $sql = "SELECT
                  idcarrito,
                  fk_idcliente
                FROM carritos ORDER BY fk_idcliente";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }

    public static function obtenerPorCliente($idcliente)
    {
        $sql = "SELECT
                  idcarrito,
                  fk_idcliente
                FROM carritos WHERE fk_idcliente = ?
                ORDER BY idcarrito DESC LIMIT 1";

        return self::construirDesdeFila(DB::selectOne($sql, [$idcliente]));
    }

    public static function cargarCompleto($idcliente)
    {
        $carrito = self::obtenerPorCliente($idcliente);
        if ($carrito == null) {
            return null;
        }

        $idcarrito = $carrito->idcarrito;

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

        $aFilas = DB::select($sql, [$idcarrito]);
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