<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cliente extends Model
{
    protected $table = 'clientes';
    public $timestamps = false;

    protected $fillable = [
        'idcliente', 'nombre', 'apellido', 'dni', 'email', 'clave', 'telefono'
    ];

    // protected $hidden = [];

    public function cargarDesdeRequest(Request $request)
    {
        $this->idcliente = $request->input('id') != "0" ? $request->input('id') : $this->idcliente;

        if ($nombre = $request->input('txtNombre'))
            $this->nombre = trim($nombre);

        if ($apellido = $request->input('txtApellido'))
            $this->apellido = trim($apellido);

        if ($dni = $request->input('txtDNI'))
            $this->dni = trim($dni);

        if ($email = $request->input('txtEmail'))
            $this->email = trim($email);

        if ($clave = $request->input('txtClave'))
            $this->clave = password_hash(trim($clave), PASSWORD_DEFAULT);

        if ($telefono = $request->input('txtTelefono'))
            $this->telefono = trim($telefono);
    }

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
        // TODO: Mejorar, definir los campos a actualizar dinamicamente.
        if ($this->clave) {
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
        } else {
            $sql = "UPDATE clientes SET
                      nombre = ?,
                      apellido = ?,
                      dni = ?,
                      email = ?,
                      telefono = ?
                    WHERE idcliente = ?";
    
            DB::update($sql, [
                $this->nombre,
                $this->apellido,
                $this->dni,
                $this->email,
                $this->telefono,
                $this->idcliente
            ]);
        }
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

    public static function contarRegistros()
    {
        $sql = "SELECT COUNT(*) AS total FROM clientes";

        if ($fila = DB::selectOne($sql)) {
            return $fila->total;
        }

        return 0;
    }

    public static function obtenerPaginado(int $inicio = 0, int $cantidad = 25)
    {
        $sql = "SELECT
                  idcliente,
                  nombre,
                  apellido,
                  dni,
                  email,
                  clave,
                  telefono
                FROM clientes ORDER BY nombre LIMIT $inicio, $cantidad";

        $lstRetorno = [];
        foreach (DB::select($sql) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}