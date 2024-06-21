<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';
    protected $primaryKey = 'idestado';

    public $timestamps = false;

    protected $fillable = [
        'idestado', 'nombre', 'color'
    ];

    public function scopeCountPedidos(Builder $query)
    {
        return $query->addSelect(['count' => Pedido::selectRaw('COUNT(*)')->whereColumn('fk_idestado', 'estados.idestado')]);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'fk_idestado');
    }

    public function insertar() {
        $sql = "INSERT INTO estados (
                  nombre,
                  color
                ) VALUES (?)";

        DB::insert($sql, [$this->nombre, $this->color]);
        $this->idestado = DB::getPdo()->lastInsertId();

        return $this->idestado;
    }

    public function actualizar() {
        $sql = "UPDATE estados SET
                  nombre = ?,
                  color = ?
                WHERE idestado = ?";

        DB::update($sql, [$this->nombre, $this->color, $this->idestado]);
    }
}