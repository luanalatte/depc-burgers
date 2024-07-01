<?php

namespace App\Entidades;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeMiCuenta(Builder $query)
    {
        return $query->select(
            'idcliente',
            'nombre',
            'apellido',
            'dni',
            'email',
            'clave',
            'telefono'
        )->with(['pedidosActivos' => function ($query) {
            $query->select('idpedido', 'fk_idcliente', 'total')->incluirEstado()->incluirSucursal();
        }]);
    }

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

    public function pedidosActivos()
    {
        return $this->hasMany(Pedido::class, 'fk_idcliente')->activo();
    }

    public function cargarDesdeRequest(Request $request)
    {
        if ($request->filled('txtNombre')) {
            $this->nombre = $request->input('txtNombre');
        }

        if ($request->filled('txtApellido')) {
            $this->apellido = $request->input('txtApellido');
        }

        if ($request->filled('txtDNI')) {
            $this->dni = $request->input('txtDNI');
        }

        if ($request->filled('txtEmail')) {
            $this->email = $request->input('txtEmail');
        }

        if ($request->has('txtClave')) {
            $claveNueva = (string) $request->input('txtClave');

            if (!$this->exists) {
                $this->clave = password_hash($claveNueva, PASSWORD_DEFAULT);
            } elseif ($request->has('txtClaveAntigua')) {
                $claveAntigua = (string) $request->input('txtClaveAntigua');
                if (password_verify($claveAntigua, $this->clave)) {
                    $this->clave = password_hash($claveNueva, PASSWORD_DEFAULT);
                }
            }
        }

        if ($request->has('txtTelefono')) {
            $this->telefono = $request->input('txtTelefono');
        }
    }

    public static function autenticado()
    {
        return Session::get('cliente_id');
    }
}