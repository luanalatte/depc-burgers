<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    protected $table = 'postulaciones';
    public $timestamps = false;

    protected $fillable = [
        'idpostulacion', 'nombre', 'apellido', 'telefono', 'email', 'archivo'
    ];

    public function insertar() {
        $sql = "INSERT INTO postulaciones (
                  nombre,
                  apellido,
                  telefono,
                  email,
                  archivo
                ) VALUES (?, ?, ?, ?, ?)";

        DB::insert($sql, [$this->nombre, $this->apellido, $this->telefono, $this->email, $this->archivo]);
        $this->idpostulacion = DB::getPdo()->lastInsertId();

        return $this->idpostulacion;
    }

    public function actualizar() {
        $sql = "UPDATE postulaciones SET
                  nombre = ?,
                  apellido = ?,
                  telefono = ?,
                  email = ?,
                  archivo = ?
                WHERE idpostulacion = ?";

        DB::update($sql, [
            $this->nombre,
            $this->apellido,
            $this->telefono,
            $this->email,
            $this->archivo,
            $this->idpostulacion
        ]);
    }

    public function eliminar() {
        $sql = "DELETE FROM postulaciones WHERE idpostulacion = ?";
        DB::delete($sql, [$this->idpostulacion]);
    }

    private static function construirDesdeFila($fila) {
        if (!$fila)
            return null;

        $postulacion = new Postulacion();
        $postulacion->idpostulacion = $fila->idpostulacion;
        $postulacion->nombre = $fila->nombre;
        $postulacion->apellido = $fila->apellido;
        $postulacion->telefono = $fila->dni;
        $postulacion->email = $fila->email;
        $postulacion->archivo = $fila->archivo;

        return $postulacion;
    }

    public static function obtenerPorId($idPostulacion)
    {
        $sql = "SELECT
                  idpostulacion,
                  nombre,
                  apellido,
                  telefono,
                  email,
                  archivo
                FROM postulaciones WHERE idpostulacion = ?";

        return self::construirDesdeFila(DB::selectOne($sql, [$idPostulacion]));
    }

    public static function obtenerTodos()
    {
        $sql = "SELECT
                  idpostulacion,
                  nombre,
                  apellido,
                  telefono,
                  email,
                  archivo
                FROM postulaciones ORDER BY nombre";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}