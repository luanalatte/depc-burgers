<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Sucursal extends Model
{
    protected $table = 'sucursales';
    protected $primaryKey = 'idsucursal';

    public $timestamps = false;

    protected $fillable = [
        'idsucursal', 'nombre', 'direccion', 'telefono', 'maps_url'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'fk_idsucursal');
    }

    protected static function booted() {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('nombre');
        });
    }

    public function scopeGrilla(Builder $query, int $orderColumnIdx = 0, string $orderDirection = "asc")
    {
        $columnas = ['nombre', 'direccion', 'telefono', 'maps_url'];

        $orderColumn = $columnas[$orderColumnIdx] ?? 'nombre';
        $orderDirection = $orderDirection == 'desc' ? 'desc' : 'asc';

        return $query->withoutGlobalScope('order')
            ->orderBy($orderColumn, $orderDirection)
            ->select(
                'idsucursal',
                'nombre',
                'direccion',
                'telefono',
                'maps_url'
            );
    }

    public function cargarDesdeRequest(Request $request)
    {
        $this->idsucursal = $request->input('id') != "0" ? $request->input('id') : $this->idsucursal;

        $this->nombre = trimIfString($request->input('txtNombre'));
        $this->direccion = trimIfString($request->input('txtDireccion'));
        $this->telefono = trimIfString($request->input('txtTelefono'));
        $this->maps_url = trimIfString($request->input('txtMapsUrl'));
    }

    public function insertar() {
        $sql = "INSERT INTO sucursales (
                  nombre,
                  direccion,
                  telefono,
                  maps_url
                ) VALUES (?, ?, ?, ?)";

        DB::insert($sql, [$this->nombre, $this->direccion, $this->telefono, $this->maps_url]);
        $this->idsucursal = DB::getPdo()->lastInsertId();

        return $this->idsucursal;
    }

    public function actualizar() {
        $sql = "UPDATE sucursales SET
                  nombre = ?,
                  direccion = ?,
                  telefono = ?,
                  maps_url = ?
                WHERE idsucursal = ?";

        DB::update($sql, [
            $this->nombre,
            $this->direccion,
            $this->telefono,
            $this->maps_url,
            $this->idsucursal
        ]);
    }
}