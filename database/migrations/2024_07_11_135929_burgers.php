<?php

use App\Entidades\Carrito;
use App\Entidades\Categoria;
use App\Entidades\Cliente;
use App\Entidades\Estado;
use App\Entidades\Pedido;
use App\Entidades\Producto;
use App\Entidades\Sucursal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id('idsucursal');
            $table->string('nombre', 50);
            $table->string('direccion', 100);
            $table->string('telefono', 50);
            $table->string('maps_url')->nullable();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id('idcategoria');
            $table->string('nombre', 50);
            $table->smallInteger('posicion')->default(0);
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id('idproducto');
            $table->foreignIdFor(Categoria::class, 'fk_idcategoria')->onDelete('restrict');
            $table->string('nombre', 50);
            $table->unsignedMediumInteger('cantidad')->nullable();
            $table->boolean('oculto')->default(false);
            $table->unsignedDecimal('precio', 10, 2);
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->id('idcliente');
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->string('dni', 30);
            $table->string('email', 50)->unique();
            $table->string('clave');
            $table->string('telefono', 50)->nullable();
        });

        Schema::create('carritos', function (Blueprint $table) {
            $table->id('idcarrito');
            $table->foreignIdFor(Cliente::class, 'fk_idcliente')->onDelete('cascade');
        });

        Schema::create('carrito_productos', function (Blueprint $table) {
            $table->foreignIdFor(Carrito::class, 'fk_idcarrito')->onDelete('cascade');
            $table->foreignIdFor(Producto::class, 'fk_idproducto')->onDelete('restrict');
            $table->unsignedSmallInteger('cantidad')->default(1);
        });

        Schema::create('estados', function (Blueprint $table) {
            $table->id('idestado');
            $table->string('nombre', 30);
            $table->string('color', 20)->nullable();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('idpedido');
            $table->foreignIdFor(Cliente::class, 'fk_idcliente')->onDelete('restrict');
            $table->foreignIdFor(Sucursal::class, 'fk_idsucursal')->onDelete('restrict');
            $table->foreignIdFor(Estado::class, 'fk_idestado')->onDelete('restrict');
            $table->timestamp('fecha')->useCurrent();
            $table->unsignedDecimal('total', 10, 2);
            $table->unsignedTinyInteger('metodo_pago')->default(0);
            $table->boolean('pagado')->default(false);
            $table->text('comentarios')->nullable();
        });

        Schema::create('pedido_productos', function (Blueprint $table) {
            $table->foreignIdFor(Pedido::class, 'fk_idpedido')->onDelete('cascade');
            $table->foreignIdFor(Producto::class, 'fk_idproducto')->onDelete('restrict');
            $table->unsignedSmallInteger('cantidad');
            $table->unsignedDecimal('precio', 10, 2);
        });

        Schema::create('postulaciones', function (Blueprint $table) {
            $table->id('idpostulacion');
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->string('domicilio', 100);
            $table->string('telefono', 30);
            $table->string('email', 50);
            $table->string('archivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carritos');
        Schema::dropIfExists('carrito_productos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('estados');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('pedido_productos');
        Schema::dropIfExists('postulaciones');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('sucursales');
    }
};