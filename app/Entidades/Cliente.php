<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    public $timestamps = false;

    protected $fillable = [
        'idcliente', 'nombre', 'apellido', 'dni', 'email', 'clave', 'telefono'
    ];

    // protected $hidden = [];

    public function insertar() {
        $sql = "INSERT INTO clientes (
                  nombre,
                  apellido,
                  dni,
                  email,
                  clave,
                  telefono
                ) VALUES (?, ?, ?, ?, ?, ?)";

        DB::insert($sql, [$this->nombre, $this->apellido, $this->dni, $this->email, $this->clave, $this->telefono]);
        $this->idcliente = DB::getPdo()->lastInsertId();

        return $this->idcliente;
    }

    public function actualizar() {
        $sql = "UPDATE clientes SET
                  nombre = ?,
                  apellido = ?,
                  dni = ?,
                  email = ?,
                  clave = ?,
                  telefono = ?
                WHERE idcliente = ?";

        DB::update($sql, [
            $this->nombre,
            $this->apellido,
            $this->dni,
            $this->email,
            $this->clave,
            $this->telefono,
            $this->idcliente
        ]);
    }

    public function eliminar() {
        $sql = "DELETE FROM clientes WHERE idcliente = ?";
        DB::delete($sql, [$this->idcliente]);
    }

    private static function construirDesdeFila($fila) {
        if (!$fila)
            return null;

        $cliente = new Cliente();
        $cliente->idcliente = $fila->idcliente;
        $cliente->nombre = $fila->nombre;
        $cliente->apellido = $fila->apellido;
        $cliente->dni = $fila->dni;
        $cliente->email = $fila->email;
        $cliente->clave = $fila->clave;
        $cliente->telefono = $fila->telefono;

        return $cliente;
    }

    public static function obtenerPorId($idCliente)
    {
        $sql = "SELECT
                  idcliente,
                  nombre,
                  apellido,
                  dni,
                  email,
                  clave,
                  telefono
                FROM clientes WHERE idcliente = ?";

        return self::construirDesdeFila(DB::selectOne($sql, [$idCliente]));
    }

    public static function obtenerTodos()
    {
        $sql = "SELECT
                  idcliente,
                  nombre,
                  apellido,
                  dni,
                  email,
                  clave,
                  telefono
                FROM clientes ORDER BY nombre";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}