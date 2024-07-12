<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $idAreaSistema = DB::table('sistema_areas')->insertGetId([
            'ncarea' => 'SISTEMAS',
            'descarea' => 'Sistemas',
        ]);

        $familiaAdminTotal = DB::table('sistema_familias')->insertGetId([
            'nombre' => 'Administrador total',
            'descripcion' => 'Administrador total'
        ]);

        $familiaCliente = DB::table('sistema_familias')->insertGetId([
            'nombre' => 'Cliente',
            'descripcion' => 'Cliente'
        ]);
        
        $familiaAdminEmpresa = DB::table('sistema_familias')->insertGetId([
            'nombre' => 'Administrador de la Empresa',
            'descripcion' => 'Administrador de la Empresa'
        ]);

        $familiaAdminParcial = DB::table('sistema_familias')->insertGetId([
            'nombre' => 'Administrativo',
            'descripcion' => 'Administrador Parcial'
        ]);

        $familiaUsuario = DB::table('sistema_familias')->insertGetId([
            'nombre' => 'Usuario',
            'descripcion' => 'Usuario'
        ]);

        $idMenuInicio = DB::table('sistema_menues')->insertGetId([
            'url' => '/admin',
            'orden' => -1,
            'nombre' => 'Inicio',
            'id_padre' => null,
            'fk_idpatente' => null,
            'css' => 'fas fa-home',
            'activo' => 1
        ]);

        $idMenuSistema = DB::table('sistema_menues')->insertGetId([
            'url' => null,
            'orden' => 100,
            'nombre' => 'Sistema',
            'id_padre' => null,
            'fk_idpatente' => null,
            'css' => 'fa fa-lock fa-fw',
            'activo' => 1
        ]);

        $idMenuClientes = DB::table('sistema_menues')->insertGetId([
            'url' => null,
            'orden' => 1,
            'nombre' => 'Clientes',
            'id_padre' => null,
            'fk_idpatente' => null,
            'css' => 'fas fa-user',
            'activo' => 1
        ]);

        $idMenuProductos = DB::table('sistema_menues')->insertGetId([
            'url' => null,
            'orden' => 2,
            'nombre' => 'Productos',
            'id_padre' => null,
            'fk_idpatente' => null,
            'css' => 'fas fa-hamburger',
            'activo' => 1
        ]);

        $idMenuPedidos = DB::table('sistema_menues')->insertGetId([
            'url' => null,
            'orden' => 3,
            'nombre' => 'Pedidos',
            'id_padre' => null,
            'fk_idpatente' => null,
            'css' => 'fas fa-shopping-cart',
            'activo' => 1
        ]);

        $idMenuPostulaciones = DB::table('sistema_menues')->insertGetId([
            'url' => null,
            'orden' => 4,
            'nombre' => 'Postulaciones',
            'id_padre' => null,
            'fk_idpatente' => null,
            'css' => 'fas fa-user-plus',
            'activo' => 1
        ]);

        $idMenuSucursales = DB::table('sistema_menues')->insertGetId([
            'url' => null,
            'orden' => 6,
            'nombre' => 'Sucursales',
            'id_padre' => null,
            'fk_idpatente' => null,
            'css' => 'fas fa-store',
            'activo' => 1
        ]);

        $menues = [
            [
                'url' => '/admin/grupos',
                'orden' => 3,
                'nombre' => 'Áreas de trabajo',
                'id_padre' => $idMenuSistema,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/usuarios',
                'orden' => 1,
                'nombre' => 'Usuarios',
                'id_padre' => $idMenuSistema,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/permisos',
                'orden' => 2,
                'nombre' => 'Permisos',
                'id_padre' => $idMenuSistema,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/sistema/menu',
                'orden' => 1,
                'nombre' => 'Menú',
                'id_padre' => $idMenuSistema,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/patentes',
                'orden' => 2,
                'nombre' => 'Patentes',
                'id_padre' => $idMenuSistema,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/cliente/nuevo',
                'orden' => 2,
                'nombre' => 'Nuevo cliente',
                'id_padre' => $idMenuClientes,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/clientes',
                'orden' => 0,
                'nombre' => 'Listado de clientes',
                'id_padre' => $idMenuClientes,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/productos',
                'orden' => 1,
                'nombre' => 'Listado de Productos',
                'id_padre' => $idMenuProductos,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/producto/nuevo',
                'orden' => 2,
                'nombre' => 'Nuevo producto',
                'id_padre' => $idMenuProductos,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/categorias',
                'orden' => 3,
                'nombre' => 'Categorías',
                'id_padre' => $idMenuProductos,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/categoria/nuevo',
                'orden' => 4,
                'nombre' => 'Nueva categoría',
                'id_padre' => $idMenuProductos,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/pedidos',
                'orden' => 1,
                'nombre' => 'Listado de pedidos',
                'id_padre' => $idMenuPedidos,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/pedido/nuevo',
                'orden' => 2,
                'nombre' => 'Nuevo pedido',
                'id_padre' => $idMenuPedidos,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/postulaciones',
                'orden' => 1,
                'nombre' => 'Listado de postulaciones',
                'id_padre' => $idMenuPostulaciones,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/postulacion/nuevo',
                'orden' => 2,
                'nombre' => 'Nueva postulación',
                'id_padre' => $idMenuPostulaciones,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/sucursales',
                'orden' => 1,
                'nombre' => 'Listado de sucursales',
                'id_padre' => $idMenuSucursales,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
            [
                'url' => '/admin/sucursal/nuevo',
                'orden' => 2,
                'nombre' => 'Nueva sucursal',
                'id_padre' => $idMenuSucursales,
                'fk_idpatente' => null,
                'css' => null,
                'activo' => 1
            ],
        ];

        DB::table('sistema_menu_area')->insert([
            ['fk_idmenu' => $idMenuSistema, 'fk_idarea' => $idAreaSistema],
            ['fk_idmenu' => $idMenuInicio, 'fk_idarea' => $idAreaSistema],
            ['fk_idmenu' => $idMenuClientes, 'fk_idarea' => $idAreaSistema],
            ['fk_idmenu' => $idMenuProductos, 'fk_idarea' => $idAreaSistema],
            ['fk_idmenu' => $idMenuPedidos, 'fk_idarea' => $idAreaSistema],
            ['fk_idmenu' => $idMenuPostulaciones, 'fk_idarea' => $idAreaSistema],
            ['fk_idmenu' => $idMenuSucursales, 'fk_idarea' => $idAreaSistema],
        ]);

        foreach ($menues as $menu) {
            $menuID = DB::table('sistema_menues')->insertGetId($menu);
            DB::table('sistema_menu_area')->insert([
                'fk_idmenu' => $menuID, 'fk_idarea' => $idAreaSistema
            ]);
        }

        function crearPatente($modulo, $submodulo, $nombre, $descripcion, $log_operacion = 1)
        {
            $consulta = DB::table('sistema_patentes')->insertGetId([
                'tipo' => 'CONSULTA',
                'modulo' => $modulo,
                'submodulo' => $submodulo,
                'nombre' => strtoupper($nombre) . 'CONSULTA',
                'descripcion' => 'Consulta de ' . $descripcion,
                'log_operacion' => $log_operacion
            ]);

            $alta = DB::table('sistema_patentes')->insertGetId([
                'tipo' => 'ALTA',
                'modulo' => $modulo,
                'submodulo' => $submodulo,
                'nombre' => strtoupper($nombre) . 'ALTA',
                'descripcion' => 'Alta de ' . $descripcion,
                'log_operacion' => $log_operacion
            ]);

            $baja = DB::table('sistema_patentes')->insertGetId([
                'tipo' => 'BAJA',
                'modulo' => $modulo,
                'submodulo' => $submodulo,
                'nombre' => strtoupper($nombre) . 'BAJA',
                'descripcion' => 'Baja de ' . $descripcion,
                'log_operacion' => $log_operacion
            ]);

            $modificacion = DB::table('sistema_patentes')->insertGetId([
                'tipo' => 'EDITAR',
                'modulo' => $modulo,
                'submodulo' => $submodulo,
                'nombre' => strtoupper($nombre) . 'MODIFICACION',
                'descripcion' => 'Modificación de ' . $descripcion,
                'log_operacion' => $log_operacion
            ]);

            return [$consulta, $alta, $baja, $modificacion];
        }

        $patentePatentes = crearPatente('Patentes', 'Patentes', 'PATENTES', 'patentes');
        $patentePermisos = crearPatente('Sistema', 'Permisos', 'PERMISOS', 'permisos');
        $patenteGrupos = crearPatente('Sistema', 'Grupo de usuarios', 'GRUPO', 'grupo de usuarios');
        $patenteMenus = crearPatente('Menu', 'Menu', 'MENU', 'menus');

        $patenteUsuarios = crearPatente('Sistema', 'Usuario', 'USUARIO', 'usuarios');
        $patenteUsuarios[] = DB::table('sistema_patentes')->insertGetId([
            'tipo' => 'EDITAR',
            'modulo' => 'Sistema',
            'submodulo' => 'Usuario',
            'nombre' => 'USUARIOAGREGARPERMISO',
            'descripcion' => 'Agrega permisos dentro de la pantalla del usuario',
            'log_operacion' => 1
        ]);

        $patenteClientes = crearPatente('Clientes', 'Clientes', 'CLIENTE', 'clientes');
        $patenteCategorias = crearPatente('Categorías', 'Categorías', 'CATEGORIA', 'categorias de productos');
        $patenteProductos = crearPatente('Productos', 'Productos', 'PRODUCTO', 'productos');
        $patenteSucursales = crearPatente('Sucursales', 'Sucursales', 'SUCURSAL', 'sucursales');
        $patentePedidos = crearPatente('Pedidos', 'Pedidos', 'PEDIDO', 'pedidos');
        $patentePostulacion = crearPatente('Postulacion', 'Postulacion', 'POSTULANTE', 'postulaciones');

        $todasPatentes = array_merge(
            $patentePatentes,
            $patentePermisos,
            $patenteGrupos,
            $patenteMenus,
            $patenteUsuarios,
            $patenteClientes,
            $patenteCategorias,
            $patenteProductos,
            $patenteSucursales,
            $patentePedidos,
            $patentePostulacion
        );

        foreach ($todasPatentes as $id) {
            DB::table('sistema_patente_familia')->insert([
                ['fk_idpatente' => $id, 'fk_idfamilia' => $familiaAdminTotal]
            ]);
        }

        foreach ($patenteUsuarios as $id) {
            DB::table('sistema_patente_familia')->insert([
                ['fk_idpatente' => $id, 'fk_idfamilia' => $familiaAdminParcial]
            ]);
        }

        DB::table('sistema_patente_familia')->insert([
            ['fk_idpatente' => $patenteUsuarios[1], 'fk_idfamilia' => $familiaAdminEmpresa],
            ['fk_idpatente' => $patenteUsuarios[3], 'fk_idfamilia' => $familiaAdminEmpresa],
            ['fk_idpatente' => $patenteUsuarios[1], 'fk_idfamilia' => $familiaUsuario],
            ['fk_idpatente' => $patenteUsuarios[3], 'fk_idfamilia' => $familiaUsuario],
        ]);

        $idAdmin = DB::table('sistema_usuarios')->insertGetId([
            'usuario' => 'admin',
            'nombre' => 'Administrador',
            'apellido' => '',
            'mail' => 'admin@correo.com',
            'clave' => password_hash('1234', PASSWORD_DEFAULT),
            'root' => 1,
            'created_at' => '2021-09-17 16:05:57',
            'cantidad_bloqueo' => 0,
            'areapredeterminada' => 1,
            'activo' => 1,
        ]);

        DB::table('sistema_usuario_familia')->insert([
            'fk_idusuario' => $idAdmin,
            'fk_idfamilia' => $familiaAdminTotal,
            'fk_idarea' => $idAreaSistema
        ]);
    }
}
