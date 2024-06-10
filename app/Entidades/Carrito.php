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

    public function cargarProductos()
    {
        $sql = "SELECT fk_idproducto, cantidad FROM carrito_productos WHERE fk_idcarrito = ?";

        $filas = DB::select($sql, [$this->idcarrito]);
        foreach ($filas as $fila) {
            $this->aProductos[] = [Producto::obtenerPorId($fila->fk_idproducto), $fila->cantidad];
        }
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

    public static function obtenerPorCliente(Cliente $cliente)
    {
        $sql = "SELECT
                  idcarrito,
                  fk_idcliente
                FROM carritos WHERE fk_idcliente = ?";

        return self::construirDesdeFila(DB::selectOne($sql, [$cliente->idcliente]));
    }
}