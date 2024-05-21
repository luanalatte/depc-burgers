<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    public $timestamps = false;

    protected $fillable = [
        'idpedido', 'fk_idcliente', 'fk_idsucursal', 'fk_idestado', 'fecha', 'total', 'comentarios'
    ];

    // protected $hidden = [];

    public function insertar() {
        $sql = "INSERT INTO pedidos (
                  fk_idcliente,
                  fk_idsucursal,
                  fk_idestado,
                  fecha,
                  total,
                  comentarios
                ) VALUES (?, ?, ?, ?, ?, ?)";

        DB::insert($sql, [$this->fk_idcliente, $this->fk_idsucursal, $this->fk_idestado, $this->fecha, $this->total, $this->comentarios]);
        $this->idpedido = DB::getPdo()->lastInsertId();

        return $this->idpedido;
    }

    public function actualizar() {
        $sql = "UPDATE pedidos SET
                  fk_idcliente = ?,
                  fk_idsucursal = ?,
                  fk_idestado = ?,
                  fecha = ?,
                  total = ?,
                  comentarios = ?
                WHERE idpedido = ?";

        DB::update($sql, [
            $this->fk_idcliente,
            $this->fk_idsucursal,
            $this->fk_idestado,
            $this->fecha,
            $this->total,
            $this->comentarios,
            $this->idpedido
        ]);
    }

    public function eliminar() {
        $sql = "DELETE FROM pedidos WHERE idpedido = ?";
        DB::delete($sql, [$this->idpedido]);
    }

    private static function construirDesdeFila($fila) {
        if (!$fila)
            return null;

        $pedido = new Pedido();
        $pedido->idpedido = $fila->idpedido;
        $pedido->fk_idcliente = $fila->fk_idcliente;
        $pedido->fk_idsucursal = $fila->fk_idsucursal;
        $pedido->fk_idestado = $fila->fk_idestado;
        $pedido->fecha = $fila->fecha;
        $pedido->total = $fila->total;
        $pedido->comentarios = $fila->comentarios;

        return $pedido;
    }

    public static function obtenerPorId($idpedido)
    {
        $sql = "SELECT
                  idpedido,
                  fk_idcliente,
                  fk_idsucursal,
                  fk_idestado,
                  fecha,
                  total,
                  comentarios
                FROM pedidos WHERE idpedido = ?";

        return self::construirDesdeFila(DB::selectOne($sql, [$idpedido]));
    }

    public static function obtenerTodos()
    {
        $sql = "SELECT
                  idpedido,
                  fk_idcliente,
                  fk_idsucursal,
                  fk_idestado,
                  fecha,
                  total,
                  comentarios
                FROM pedidos ORDER BY fk_idcliente";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}