<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';
    protected $primaryKey = 'idcarrito';

    public $timestamps = false;

    protected $fillable = [
        'idcarrito', 'fk_idcliente'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'fk_idcliente');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'carrito_productos', 'fk_idcarrito', 'fk_idproducto')->withPivot('cantidad');
    }

    public function getTotalAttribute()
    {
        $sum = 0;
        foreach ($this->productos as $producto) {
            $sum += $producto->precio * $producto->pivot->cantidad;
        }
        return $sum;
    }
}