<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

require_once app_path() . "/start/funciones_generales.php";

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'idcliente';

    public $timestamps = false;

    protected $fillable = [
        'idcliente', 'nombre', 'apellido', 'dni', 'email', 'clave', 'telefono'
    ];

    public function scopeGrilla(Builder $query, int $orderColumnIdx = 0, string $orderDirection = "asc")
    {
        $columnas = ['nombre', 'apellido', 'dni', 'email', 'telefono'];

        $orderColumn = $columnas[$orderColumnIdx] ?? 'nombre';
        $orderDirection = $orderDirection == 'desc' ? 'desc' : 'asc';

        return $query->withoutGlobalScope('order')
            ->orderBy($orderColumn, $orderDirection)
            ->select(
                'idcliente',
                'nombre',
                'apellido',
                'dni',
                'email',
                'telefono'
            );
    }

    public function carrito()
    {
        return $this->hasOne(Carrito::class, 'fk_idcliente')->latestOfMany('idcarrito');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'fk_idcliente');
    }

    public function cargarDesdeRequest(Request $request)
    {
        $this->idcliente = $request->input('id') != "0" ? $request->input('id') : $this->idcliente;

        // No nulleables
        if ($request->filled('txtNombre'))
            $this->nombre = trimIfString($request->input('txtNombre'));

        if ($request->filled('txtApellido'))
            $this->apellido = trimIfString($request->input('txtApellido'));

        if ($request->filled('txtDNI'))
            $this->dni = trimIfString($request->input('txtDNI'));

        if ($request->filled('txtEmail'))
            $this->email = trimIfString($request->input('txtEmail'));

        if (is_null($this->idcliente)) {
            // Creando cliente, nueva clave
            $this->clave = password_hash(trimIfString($request->input('txtClave')), PASSWORD_DEFAULT);
        } elseif ($request->filled('txtClave') && $request->filled('txtClaveAntigua')) {
            // Editando cliente, cambiando clave
            $claveNueva = trimIfString($request->input('txtClave'));
            $claveAntigua = trimIfString($request->input('txtClaveAntigua'));

            if (is_string($claveNueva) && is_string($claveAntigua) && $this->verificarClave($claveAntigua)) {
                $this->clave = password_hash($claveNueva, PASSWORD_DEFAULT);
            }
        }

        // Nulleables
        if ($request->has('txtTelefono'))
            $this->telefono = trimIfString($request->input('txtTelefono'));
    }

    public function verificarClave($clave)
    {
        $claveDB = $this->cargarClave();

        return !is_null($claveDB) && password_verify($clave, $claveDB);
    }

    public function cargarClave()
    {
        if (!isset($this->idcliente))
            return null;

        $sql = "SELECT clave FROM clientes WHERE idcliente = ?";

        if ($fila = DB::selectOne($sql, [$this->idcliente])) {
            $this->clave = $fila->clave;
            return $this->clave;
        }

        return null;
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
        $aCampos = [];
        $aValores = [];

        if (isset($this->nombre)) {
            $aCampos[] = "nombre = ?";
            $aValores[] = $this->nombre;
        }

        if (isset($this->apellido)) {
            $aCampos[] = "apellido = ?";
            $aValores[] = $this->apellido;
        }

        if (isset($this->dni)) {
            $aCampos[] = "dni = ?";
            $aValores[] = $this->dni;
        }

        if (isset($this->email)) {
            $aCampos[] = "email = ?";
            $aValores[] = $this->email;
        }

        if (isset($this->clave)) {
            $aCampos[] = "clave = ?";
            $aValores[] = $this->clave;
        }

        if (isset($this->telefono)) {
            $aCampos[] = "telefono = ?";
            $aValores[] = $this->telefono;
        }

        if (empty($aCampos)) {
            return;
        }

        $aValores[] = $this->idcliente;

        $sql = "UPDATE clientes SET " . implode(", ", $aCampos) . " WHERE idcliente = ?";

        DB::update($sql, $aValores);
    }

    public static function autenticado()
    {
        return Session::get('cliente_id');
    }
}