<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    protected $table = 'postulaciones';
    protected $primaryKey = 'idpostulacion';

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
}