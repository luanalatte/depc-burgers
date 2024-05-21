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
}