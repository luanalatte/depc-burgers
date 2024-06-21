<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';
    protected $primaryKey = 'idestado';

    public $timestamps = false;

    protected $fillable = [
        'idestado', 'nombre', 'color'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'fk_idestado');
    }
}