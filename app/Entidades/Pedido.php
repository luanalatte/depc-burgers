<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'idpedido';

    public $timestamps = false;

    protected $fillable = [
        'idpedido', 'fk_idcliente', 'fk_idsucursal', 'fk_idestado', 'fecha', 'total', 'comentarios'
    ];

    public function scopeIncluirCliente(Builder $query)
    {
        if(is_null($query->getQuery()->columns)){
            $query->addSelect('*');
        }

        return $query
            ->join('clientes', 'pedidos.fk_idcliente', '=', 'clientes.idcliente')
            ->addSelect(DB::raw('CONCAT(clientes.nombre, " ", clientes.apellido) AS cliente'));
    }

    public function scopeIncluirSucursal(Builder $query)
    {
        if(is_null($query->getQuery()->columns)){
            $query->addSelect('*');
        }

        return $query
            ->join('sucursales', 'pedidos.fk_idsucursal', '=', 'sucursales.idsucursal')
            ->addSelect('sucursales.nombre AS sucursal');
    }

    public function scopeIncluirEstado(Builder $query)
    {
        if(is_null($query->getQuery()->columns)){
            $query->addSelect('*');
        }

        return $query
            ->join('estados', 'pedidos.fk_idestado', '=', 'estados.idestado')
            ->addSelect('estados.nombre AS estado');
    }

    public function scopeFiltrarEstado(Builder $query, int $idestado = null)
    {
        if ($idestado != null) {
            $query->where('fk_idestado', $idestado);
        }

        return $query;
    }

    public function scopeFiltrarSucursal(Builder $query, int $idsucursal = null)
    {
        if ($idsucursal != null) {
            $query->where('fk_idsucursal', $idsucursal);
        }

        return $query;
    }

    public function scopeFiltrarPeriodo(Builder $query, string $fechaDesde = null, string $fechaHasta = null)
    {
        if ($fechaDesde) {
            $query->where('fecha', '>=', $fechaDesde);
        }

        if ($fechaHasta) {
            $query->where('fecha', '<=', $fechaHasta);
        }

        return $query;
    }

    public function scopeGrilla(Builder $query, int $orderColumnIdx = 0, string $orderDirection = "desc")
    {
        $columnas = ['idpedido', 'cliente', 'sucursal', 'estado', 'fecha', 'total'];

        $orderColumn = $columnas[$orderColumnIdx] ?? 'fecha';
        $orderDirection = $orderDirection == 'asc' ? 'asc' : 'desc';

        return $query->withoutGlobalScope('order')
            ->orderBy($orderColumn, $orderDirection)
            ->select('idpedido', 'fk_idestado', 'fecha', 'total')
            ->incluirCliente()
            ->incluirSucursal()
            ->incluirEstado();
    }

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
}