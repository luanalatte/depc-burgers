<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';
    public $timestamps = false;

    protected $fillable = [
        'idestado', 'nombre'
    ];

    // protected $hidden = [];

    public function insertar() {
        $sql = "INSERT INTO estados (
                  nombre
                ) VALUES (?)";

        DB::insert($sql, [$this->nombre]);
        $this->idestado = DB::getPdo()->lastInsertId();

        return $this->idestado;
    }

    public function actualizar() {
        $sql = "UPDATE estados SET
                  nombre = ?
                WHERE idestado = ?";

        DB::update($sql, [$this->nombre, $this->idestado]);
    }

    public function eliminar() {
        $sql = "DELETE FROM estados WHERE idestado = ?";
        DB::delete($sql, [$this->idestado]);
    }

    private static function construirDesdeFila($fila) {
        if (!$fila)
            return null;

        $estado = new Estado();
        $estado->idestado = $fila->idestado;
        $estado->nombre = $fila->nombre;

        return $estado;
    }

    public static function obtenerPorId($idestado)
    {
        $sql = "SELECT
                  idestado,
                  nombre
                FROM estados WHERE idestado = ?";

        return self::construirDesdeFila(DB::selectOne($sql, [$idestado]));
    }

    public static function obtenerTodos()
    {
        $sql = "SELECT
                  idestado,
                  nombre
                FROM estados ORDER BY nombre";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}