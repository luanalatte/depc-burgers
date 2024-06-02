<?php

namespace App\Entidades;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Pedido extends Model
{
    protected $table = 'pedidos';
    public $timestamps = false;

    protected $fillable = [
        'idpedido', 'fk_idcliente', 'fk_idsucursal', 'fk_idestado', 'fecha', 'total', 'comentarios'
    ];

    public $cliente;
    public $sucursal;
    public $nombre;

    // protected $hidden = [];

    public function cargarDesdeRequest(Request $request)
    {
        $this->idpedido = $request->input('id') != "0" ? $request->input('id') : $this->idpedido;

        if ($request->filled('lstCliente'))
            $this->fk_idcliente = $request->input('lstCliente');

        if ($request->filled('lstSucursal'))
            $this->fk_idsucursal = $request->input('lstSucursal');

        if ($request->filled('lstEstado'))
            $this->fk_idestado = $request->input('lstEstado');

        if ($request->filled('txtFecha'))
            $this->fecha = trimIfString($request->input('txtFecha'));

        if ($request->filled('txtTotal'))
            $this->total = trimIfString($request->input('txtTotal'));
        
        if ($request->has('txtComentarios'))
            $this->comentarios = trimIfString($request->input('txtComentarios'));
    }

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
        $aCampos = [];
        $aValores = [];

        if (isset($this->fk_idcliente)) {
            $aCampos[] = "fk_idcliente = ?";
            $aValores[] = $this->fk_idcliente;
        }

        if (isset($this->fk_idsucursal)) {
            $aCampos[] = "fk_idsucursal = ?";
            $aValores[] = $this->fk_idsucursal;
        }

        if (isset($this->fk_idestado)) {
            $aCampos[] = "fk_idestado = ?";
            $aValores[] = $this->fk_idestado;
        }

        if (isset($this->fecha)) {
            $aCampos[] = "fecha = ?";
            $aValores[] = $this->fecha;
        }

        if (isset($this->total)) {
            $aCampos[] = "total = ?";
            $aValores[] = $this->total;
        }

        if (isset($this->comentarios)) {
            $aCampos[] = "comentarios = ?";
            $aValores[] = $this->comentarios;
        }

        if (empty($aCampos)) {
            return;
        }

        $aValores[] = $this->idpedido;

        $sql = "UPDATE pedidos SET " . implode(", ", $aCampos) . " WHERE idpedido = ?";

        DB::update($sql, $aValores);
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

        $pedido->cliente = $fila->cliente ?? null;
        $pedido->sucursal = $fila->sucursal ?? null;
        $pedido->estado = $fila->estado ?? null;

        return $pedido;
    }

    public static function obtenerPorId($idpedido)
    {
        $sql = "SELECT
                  A.idpedido,
                  A.fk_idcliente,
                  A.fk_idsucursal,
                  A.fk_idestado,
                  A.fecha,
                  A.total,
                  A.comentarios,
                  CONCAT(B.nombre, ' ', B.apellido) AS cliente,
                  C.nombre AS sucursal
                FROM pedidos A
                LEFT JOIN clientes B ON A.fk_idcliente = B.idcliente
                LEFT JOIN sucursales C ON A.fk_idsucursal = C.idsucursal
                WHERE idpedido = ?";

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

    public static function contarRegistros($estado = 0, $sucursal = 0)
    {
        $sql = "SELECT COUNT(*) AS total FROM pedidos";
        $aValores = [];
        $aFiltros = [];

        if ($estado) {
            $aFiltros[] = "fk_idestado = ?";
            $aValores[] = $estado;
        }

        if ($sucursal) {
            $aFiltros[] = "fk_idsucursal = ?";
            $aValores[] = $sucursal;
        }

        if ($aFiltros) {
            $sql .= " WHERE " . implode(' AND ', $aFiltros);
        }

        if ($fila = DB::selectOne($sql, $aValores)) {
            return $fila->total;
        }

        return 0;
    }

    public static function obtenerFiltrado($estado = 0, $sucursal = 0, int $inicio = 0, int $cantidad = 25)
    {
        $sql = "SELECT
                  A.idpedido,
                  A.fk_idcliente,
                  A.fk_idsucursal,
                  A.fk_idestado,
                  A.fecha,
                  A.total,
                  A.comentarios,
                  CONCAT(B.nombre, ' ', B.apellido) AS cliente,
                  C.nombre AS sucursal,
                  D.nombre AS estado
                FROM pedidos A
                INNER JOIN clientes B ON A.fk_idcliente = B.idcliente
                INNER JOIN sucursales C ON A.fk_idsucursal = C.idsucursal
                INNER JOIN estados D ON A.fk_idestado = D.idestado";

        $aValores = [];
        $aFiltros = [];

        if ($estado) {
            $aFiltros[] = "fk_idestado = ?";
            $aValores[] = $estado;
        }

        if ($sucursal) {
            $aFiltros[] = "fk_idsucursal = ?";
            $aValores[] = $sucursal;
        }

        if ($aFiltros) {
            $sql .= " WHERE " . implode(' AND ', $aFiltros);
        }

        $sql .= " ORDER BY A.fecha DESC LIMIT $inicio, $cantidad";

        $lstRetorno = [];
        foreach (DB::select($sql, $aValores) as $fila) {
            $lstRetorno[] = self::construirDesdeFila($fila);
        }

        return $lstRetorno;
    }
}