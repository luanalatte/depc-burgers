<?php
 //use Carbon\Carbon; 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

/*Route::get('/time' , function(){$date =new Carbon;echo $date ; } );*/

use Illuminate\Support\Facades\Route;


/* --------------------------------------------- */
/* WEB ECOMMERCE                                 */
/* --------------------------------------------- */

Route::get('/', 'ControladorWebHome@index');
Route::get('/takeaway', 'ControladorWebTakeaway@index');
Route::get('/nosotros', 'ControladorWebNosotros@index');
Route::post('/nosotros', 'ControladorWebNosotros@postular');
Route::get('/contacto', 'ControladorWebContacto@index');
Route::post('/contacto', 'ControladorWebContacto@contactar');
Route::get('/login', 'ControladorWebLogin@index');
Route::post('/login', 'ControladorWebLogin@login');
Route::get('/logout', 'ControladorWebLogin@logout');
Route::get('/registrarse', 'ControladorWebRegistro@index');
Route::post('/registrarse', 'ControladorWebRegistro@guardar');
Route::get('/recuperar-clave', 'ControladorWebLogin@getRecuperarClave');
Route::post('/recuperar-clave', 'ControladorWebLogin@postRecuperarClave');

Route::middleware(['auth'])->group(function() {

    Route::get('/micuenta', 'ControladorWebMiCuenta@index');
    Route::post('/micuenta', 'ControladorWebMiCuenta@guardar');
    Route::get('/cambiar-clave', 'ControladorWebMiCuenta@getCambiarClave');
    Route::post('/cambiar-clave', 'ControladorWebMiCuenta@postCambiarClave');
    Route::get('/carrito', 'ControladorWebCarrito@index')->name('carrito');
    Route::post('/carrito/editar', 'ControladorWebCarrito@editar')->name('carrito.editar');
    Route::post('/carrito/confirmar', 'ControladorWebCarrito@confirmar')->name('carrito.confirmar');

    Route::get('/mercadopago/pagar/{id}', 'ControladorMercadoPago@pagar')->name('mercadopago.pagar');
    Route::get('/mercadopago/aprobado/{id}', 'ControladorMercadoPago@aprobado')->name('mercadopago.aprobado');
    Route::get('/mercadopago/pendiente/{id}', 'ControladorMercadoPago@pendiente')->name('mercadopago.pendiente');
    Route::get('/mercadopago/error/{id}', 'ControladorMercadoPago@error')->name('mercadopago.error');

});

Route::group(array('domain' => '127.0.0.1'), function () {

/* --------------------------------------------- */
/* CONTROLADOR LOGIN                             */
/* --------------------------------------------- */
Route::get('/admin', 'ControladorHome@index');

Route::get('/admin/login', 'ControladorLogin@index');
Route::get('/admin/logout', 'ControladorLogin@logout');
Route::post('/admin/logout', 'ControladorLogin@entrar');
Route::post('/admin/login', 'ControladorLogin@entrar');

/* --------------------------------------------- */
/* CONTROLADOR RECUPERO CLAVE                    */
/* --------------------------------------------- */
Route::get('/admin/recupero-clave', 'ControladorRecuperoClave@index');
Route::post('/admin/recupero-clave', 'ControladorRecuperoClave@recuperar');

/* --------------------------------------------- */
/* CONTROLADOR PERMISO                           */
/* --------------------------------------------- */
Route::get('/admin/usuarios/cargarGrillaFamiliaDisponibles', 'ControladorPermiso@cargarGrillaFamiliaDisponibles')->name('usuarios.cargarGrillaFamiliaDisponibles');
Route::get('/admin/usuarios/cargarGrillaFamiliasDelUsuario', 'ControladorPermiso@cargarGrillaFamiliasDelUsuario')->name('usuarios.cargarGrillaFamiliasDelUsuario');
Route::get('/admin/permisos', 'ControladorPermiso@index');
Route::get('/admin/permisos/cargarGrilla', 'ControladorPermiso@cargarGrilla')->name('permiso.cargarGrilla');
Route::get('/admin/permiso/nuevo', 'ControladorPermiso@nuevo');
Route::get('/admin/permiso/cargarGrillaPatentesPorFamilia', 'ControladorPermiso@cargarGrillaPatentesPorFamilia')->name('permiso.cargarGrillaPatentesPorFamilia');
Route::get('/admin/permiso/cargarGrillaPatentesDisponibles', 'ControladorPermiso@cargarGrillaPatentesDisponibles')->name('permiso.cargarGrillaPatentesDisponibles');
Route::get('/admin/permiso/{idpermiso}', 'ControladorPermiso@editar');
Route::post('/admin/permiso/{idpermiso}', 'ControladorPermiso@guardar');

/* --------------------------------------------- */
/* CONTROLADOR GRUPO                             */
/* --------------------------------------------- */
Route::get('/admin/grupos', 'ControladorGrupo@index');
Route::get('/admin/usuarios/cargarGrillaGruposDelUsuario', 'ControladorGrupo@cargarGrillaGruposDelUsuario')->name('usuarios.cargarGrillaGruposDelUsuario'); //otra cosa
Route::get('/admin/usuarios/cargarGrillaGruposDisponibles', 'ControladorGrupo@cargarGrillaGruposDisponibles')->name('usuarios.cargarGrillaGruposDisponibles'); //otra cosa
Route::get('/admin/grupos/cargarGrilla', 'ControladorGrupo@cargarGrilla')->name('grupo.cargarGrilla');
Route::get('/admin/grupo/nuevo', 'ControladorGrupo@nuevo');
Route::get('/admin/grupo/setearGrupo', 'ControladorGrupo@setearGrupo');
Route::post('/admin/grupo/nuevo', 'ControladorGrupo@guardar');
Route::get('/admin/grupo/{idgrupo}', 'ControladorGrupo@editar');
Route::post('/admin/grupo/{idgrupo}', 'ControladorGrupo@guardar');

/* --------------------------------------------- */
/* CONTROLADOR USUARIO                           */
/* --------------------------------------------- */
Route::get('/admin/usuarios', 'ControladorUsuario@index');
Route::get('/admin/usuarios/nuevo', 'ControladorUsuario@nuevo');
Route::post('/admin/usuarios/nuevo', 'ControladorUsuario@guardar');
Route::post('/admin/usuarios/{usuario}', 'ControladorUsuario@guardar');
Route::get('/admin/usuarios/cargarGrilla', 'ControladorUsuario@cargarGrilla')->name('usuarios.cargarGrilla');
Route::get('/admin/usuarios/buscarUsuario', 'ControladorUsuario@buscarUsuario');
Route::get('/admin/usuarios/{usuario}', 'ControladorUsuario@editar');

/* --------------------------------------------- */
/* CONTROLADOR MENU                             */
/* --------------------------------------------- */
Route::get('/admin/sistema/menu', 'ControladorMenu@index');
Route::get('/admin/sistema/menu/nuevo', 'ControladorMenu@nuevo');
Route::post('/admin/sistema/menu/nuevo', 'ControladorMenu@guardar');
Route::get('/admin/sistema/menu/cargarGrilla', 'ControladorMenu@cargarGrilla')->name('menu.cargarGrilla');
Route::get('/admin/sistema/menu/eliminar', 'ControladorMenu@eliminar');
Route::get('/admin/sistema/menu/{id}', 'ControladorMenu@editar');
Route::post('/admin/sistema/menu/{id}', 'ControladorMenu@guardar');

});

/* --------------------------------------------- */
/* CONTROLADOR PATENTES                          */
/* --------------------------------------------- */
Route::get('/admin/patentes', 'ControladorPatente@index');
Route::get('/admin/patente/nuevo', 'ControladorPatente@nuevo');
Route::post('/admin/patente/nuevo', 'ControladorPatente@guardar');
Route::get('/admin/patente/cargarGrilla', 'ControladorPatente@cargarGrilla')->name('patente.cargarGrilla');
Route::get('/admin/patente/eliminar', 'ControladorPatente@eliminar');
Route::get('/admin/patente/nuevo/{id}', 'ControladorPatente@editar');
Route::post('/admin/patente/nuevo/{id}', 'ControladorPatente@guardar');

Route::middleware(['auth.sistema'])->group(function() {

/* --------------------------------------------- */
/* CONTROLADOR CLIENTE                           */
/* --------------------------------------------- */
    Route::get('/admin/clientes', 'ControladorCliente@index')->middleware(['autorizar:CLIENTECONSULTA']);
    Route::get('/admin/cliente/nuevo', 'ControladorCliente@nuevo')->middleware(['autorizar:CLIENTEALTA']);
    Route::post('/admin/cliente/nuevo', 'ControladorCliente@guardar')->middleware(['autorizar:CLIENTEALTA']);
    Route::get('/admin/clientes/cargarGrilla', 'ControladorCliente@cargarGrilla')
        ->middleware(['autorizar.json:CLIENTECONSULTA'])
        ->name('clientes.cargarGrilla');
    Route::get('/admin/cliente/eliminar', 'ControladorCliente@eliminar')->middleware(['autorizar:CLIENTEBAJA']);
    Route::get('/admin/cliente/{id}', 'ControladorCliente@editar')->middleware(['autorizar:CLIENTECONSULTA']);
    Route::post('/admin/cliente/{id}', 'ControladorCliente@guardar')->middleware(['autorizar:CLIENTEMODIFICACION']);

/* --------------------------------------------- */
/* CONTROLADOR PRODUCTO                          */
/* --------------------------------------------- */
    Route::get('/admin/productos', 'ControladorProducto@index')->middleware(['autorizar:PRODUCTOCONSULTA']);
    Route::get('/admin/producto/nuevo', 'ControladorProducto@nuevo')->middleware(['autorizar:PRODUCTOALTA']);
    Route::post('/admin/producto/nuevo', 'ControladorProducto@guardar')->middleware(['autorizar:PRODUCTOALTA']);
    Route::get('/admin/productos/cargarGrilla', 'ControladorProducto@cargarGrilla')
        ->middleware(['autorizar.json:PRODUCTOCONSULTA'])
        ->name('productos.cargarGrilla');
    Route::get('/admin/producto/eliminar', 'ControladorProducto@eliminar')->middleware(['autorizar:PRODUCTOBAJA']);
    Route::get('/admin/producto/{id}', 'ControladorProducto@editar')->middleware(['autorizar:PRODUCTOCONSULTA']);
    Route::post('/admin/producto/{id}', 'ControladorProducto@guardar')->middleware(['autorizar:PRODUCTOMODIFICACION']);

/* --------------------------------------------- */
/* CONTROLADOR PEDIDO                            */
/* --------------------------------------------- */
    Route::get('/admin/pedidos', 'ControladorPedido@index')->middleware(['autorizar:PEDIDOCONSULTA']);
    Route::get('/admin/pedido/nuevo', 'ControladorPedido@nuevo')->middleware(['autorizar:PEDIDOALTA']);
    Route::post('/admin/pedido/nuevo', 'ControladorPedido@guardar')->middleware(['autorizar:PEDIDOALTA']);
    Route::get('/admin/pedidos/cargarGrilla', 'ControladorPedido@cargarGrilla')
        ->middleware(['autorizar.json:PEDIDOCONSULTA'])
        ->name('pedidos.cargarGrilla');
    Route::get('/admin/pedido/setEstado', 'ControladorPedido@setEstado')
        ->middleware(['autorizar.json:PEDIDOMODIFICACION'])
        ->name('pedidos.setEstado');
    Route::get('/admin/pedido/setPagado', 'ControladorPedido@setPagado')
        ->middleware(['autorizar.json:PEDIDOMODIFICACION'])
        ->name('pedidos.setPagado');
    Route::get('/admin/pedido/eliminar', 'ControladorPedido@eliminar')->middleware(['autorizar:PEDIDOBAJA']);
    Route::get('/admin/pedido/{id}', 'ControladorPedido@editar')->middleware(['autorizar:PEDIDOCONSULTA']);
    Route::post('/admin/pedido/{id}', 'ControladorPedido@guardar')->middleware(['autorizar:PEDIDOMODIFICACION']);

/* --------------------------------------------- */
/* CONTROLADOR POSTULACION                       */
/* --------------------------------------------- */
    Route::get('/admin/postulaciones', 'ControladorPostulacion@index')->middleware(['autorizar:POSTULANTECONSULTA']);
    // Route::get('/admin/postulacion/nuevo', 'ControladorPostulacion@nuevo')->middleware(['autorizar:POSTULANTEALTA']);
    // Route::post('/admin/postulacion/nuevo', 'ControladorPostulacion@guardar')->middleware(['autorizar:POSTULANTEALTA']);
    Route::get('/admin/postulaciones/cargarGrilla', 'ControladorPostulacion@cargarGrilla')
        ->middleware(['autorizar.json:POSTULANTECONSULTA'])
        ->name('postulaciones.cargarGrilla');
    Route::get('/admin/postulacion/eliminar', 'ControladorPostulacion@eliminar')
        ->middleware(['autorizar:POSTULANTEBAJA'])
        ->name('postulaciones.eliminar');
    Route::get('/admin/postulacion/descargar/{id}', 'ControladorPostulacion@descargar')
        ->middleware(['autorizar:POSTULANTECONSULTA'])
        ->name('postulaciones.descargar');
    Route::get('/admin/postulacion/{id}', 'ControladorPostulacion@editar')->middleware(['autorizar:POSTULANTECONSULTA']);
    // Route::post('/admin/postulacion/{id}', 'ControladorPostulacion@guardar')->middleware(['autorizar:POSTULANTEMODIFICACION']);

/* --------------------------------------------- */
/* CONTROLADOR SUCURSAL                          */
/* --------------------------------------------- */
    Route::get('/admin/sucursales', 'ControladorSucursal@index')->middleware(['autorizar:SUCURSALCONSULTA']);
    Route::get('/admin/sucursal/nuevo', 'ControladorSucursal@nuevo')->middleware(['autorizar:SUCURSALALTA']);
    Route::post('/admin/sucursal/nuevo', 'ControladorSucursal@guardar')->middleware(['autorizar:SUCURSALALTA']);
    Route::get('/admin/sucursales/cargarGrilla', 'ControladorSucursal@cargarGrilla')
        ->middleware(['autorizar.json:SUCURSALCONSULTA'])
        ->name('sucursales.cargarGrilla');
    Route::get('/admin/sucursal/eliminar', 'ControladorSucursal@eliminar')->middleware(['autorizar:SUCURSALBAJA']);
    Route::get('/admin/sucursal/{id}', 'ControladorSucursal@editar')->middleware(['autorizar:SUCURSALCONSULTA']);
    Route::post('/admin/sucursal/{id}', 'ControladorSucursal@guardar')->middleware(['autorizar:SUCURSALMODIFICACION']);

/* --------------------------------------------- */
/* CONTROLADOR CATEGORIA                         */
/* --------------------------------------------- */
    Route::get('/admin/categorias', 'ControladorCategoria@index')->middleware(['autorizar:CATEGORIACONSULTA']);
    Route::get('/admin/categoria/nuevo', 'ControladorCategoria@nuevo')->middleware(['autorizar:CATEGORIAALTA']);
    Route::post('/admin/categoria/nuevo', 'ControladorCategoria@guardar')->middleware(['autorizar:CATEGORIAALTA']);
    Route::get('/admin/categorias/cargarGrilla', 'ControladorCategoria@cargarGrilla')
        ->middleware(['autorizar.json:CATEGORIACONSULTA'])
        ->name('categorias.cargarGrilla');
    Route::get('/admin/categoria/eliminar', 'ControladorCategoria@eliminar')->middleware(['autorizar:CATEGORIABAJA']);
    Route::get('/admin/categoria/{id}', 'ControladorCategoria@editar')->middleware(['autorizar:CATEGORIACONSULTA']);
    Route::post('/admin/categoria/{id}', 'ControladorCategoria@guardar')->middleware(['autorizar:CATEGORIAMODIFICACION']);
});