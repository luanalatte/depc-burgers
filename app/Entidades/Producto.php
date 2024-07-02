<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

require_once app_path() . "/start/funciones_generales.php";

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'idproducto';

    public $timestamps = false;

    protected $fillable = [
        'idproducto', 'fk_idcategoria', 'nombre', 'cantidad', 'oculto', 'precio', 'descripcion', 'imagen'
    ];

    protected $casts = [
        'cantidad' => 'int',
        'oculto' => 'bool',
        'precio' => 'float',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'fk_idcategoria');
    }

    public function carritos()
    {
        return $this->belongsToMany(Carrito::class, 'carrito_productos', 'fk_idproducto', 'fk_idcarrito');
    }

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_productos', 'fk_idproducto', 'fk_idpedido');
    }

    public function scopeOrderByCategoria(Builder $query)
    {
        return $query->withoutGlobalScope('order')
            ->join('categorias', 'productos.fk_idcategoria', '=', 'categorias.idcategoria')
            ->orderBy('categorias.posicion', 'desc');
    }

    public function scopeTakeaway(Builder $query)
    {
        return $query->select(
            'productos.idproducto',
            'productos.fk_idcategoria',
            'productos.nombre',
            'productos.cantidad',
            'productos.precio',
            'productos.descripcion',
            'productos.imagen'
        )->where('oculto', false)->orderByCategoria();
    }

    public function scopeGrilla(Builder $query, int $orderColumnIdx = 0, string $orderDirection = "asc")
    {
        $columnas = ['nombre', 'categoria', 'cantidad', 'oculto', 'precio', 'descripcion'];

        $orderColumn = $columnas[$orderColumnIdx] ?? 'nombre';
        $orderDirection = $orderDirection == 'desc' ? 'desc' : 'asc';

        return $query->withoutGlobalScope('order')
            ->join('categorias', 'productos.fk_idcategoria', '=', 'categorias.idcategoria')
            ->orderBy($orderColumn, $orderDirection)
            ->select(
                'productos.idproducto',
                'productos.nombre',
                'productos.cantidad',
                'productos.oculto',
                'productos.precio',
                'productos.descripcion',
                'productos.imagen',
                'categorias.nombre AS categoria'
            );
    }

    public function cargarDesdeRequest(Request $request)
    {
        $this->fk_idcategoria = $request->input('lstCategoria');

        $this->nombre = $request->input('txtNombre');
        $this->cantidad = $request->input('txtCantidad');
        $this->oculto = $request->boolean('lstOculto');
        $this->precio = $request->input('txtPrecio');
        $this->descripcion = $request->input('txtDescripcion');
    }
}