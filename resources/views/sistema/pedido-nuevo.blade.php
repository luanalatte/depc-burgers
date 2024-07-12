@extends('sistema.plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($pedido->idpedido) && $pedido->idpedido > 0 ? $pedido->idpedido : 0; ?>';
    <?php $globalId = $pedido->idpedido ?? "0"; ?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/pedidos">Pedidos</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
    <li class="btn-item"><a title="Nuevo" href="/admin/pedido/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
    <li class="btn-item"><a title="Guardar" href="#" class="fa fa-save" aria-hidden="true" onclick="javascript: $('#modalGuardar').modal('toggle');"><span>Guardar</span></a>
    </li>
    @if($globalId > 0)
    <li class="btn-item"><a title="Eliminar" href="#" class="fa fa-trash" aria-hidden="true" onclick="javascript: $('#mdlEliminar').modal('toggle');"><span>Eliminar</span></a></li>
    @endif
    <li class="btn-item"><a title="Salir" href="#" class="fa fa-arrow-circle-left" aria-hidden="true" onclick="javascript: $('#modalSalir').modal('toggle');"><span>Salir</span></a></li>
</ol>
@endsection
@section('contenido')
@include('sistema.msg')
<div class="panel-body">
    <form id="form1" method="POST">
        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            <input type="hidden" id="id" name="id" class="form-control" value="{{$globalId}}" required>
            @if(!$globalId)
            <div class="form-group col-lg-6">
                <label>Cliente: *</label>
                <select name="lstCliente" id="lstCliente" class="form-control" required>
                    <option value="" selected disabled>Seleccionar</option>
                    @foreach ($aClientes as $cliente)
                        @if ($cliente->idcliente == $pedido->fk_idcliente)
                        <option value="{{$cliente->idcliente}}" selected>{{$cliente->nombre}} {{ $cliente->apellido }}</option>
                        @else
                        <option value="{{$cliente->idcliente}}">{{$cliente->nombre}} {{ $cliente->apellido }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-6">
                <label>Sucursal: *</label>
                <select name="lstSucursal" id="lstSucursal" class="form-control" required>
                    <option value="" selected disabled>Seleccionar</option>
                    @foreach ($aSucursales as $sucursal)
                        @if ($sucursal->idsucursal == $pedido->fk_idsucursal)
                        <option value="{{$sucursal->idsucursal}}" selected>{{$sucursal->nombre}}</option>
                        @else
                        <option value="{{$sucursal->idsucursal}}">{{$sucursal->nombre}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-6">
                <label>Estado: *</label>
                <select name="lstEstado" id="lstEstado" class="form-control" required>
                    @foreach ($aEstados as $estado)
                        @if ($estado->idestado == $pedido->fk_idestado)
                        <option value="{{$estado->idestado}}" selected>{{$estado->nombre}}</option>
                        @else
                        <option value="{{$estado->idestado}}">{{$estado->nombre}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-6">
                <label>Fecha: *</label>
                <input type="datetime-local" name="txtFecha" id="txtFecha" class="form-control" value="{{ $pedido->fecha ?? now() }}" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Total:</label>
                <input type="number" id="txtTotal" name="txtTotal" class="form-control" value="{{ $pedido->total }}" required>
            </div>
            <div class="form-group col-12">
                <label>Comentarios:</label>
                <textarea name="txtComentarios" id="txtComentarios" class="form-control" style="min-height: 6rem;">{{ $pedido->comentarios }}</textarea>
            </div>
            @else
            <div class="form-group col-lg-6">
                <label>Cliente:</label>
                <a href="/admin/cliente/{{ $pedido->fk_idcliente }}">
                    <input type="text" id='txtCliente' class="form-control" value="{{ $pedido->cliente }}" readonly>
                </a>
            </div>
            <div class="form-group col-lg-6">
                <label>Sucursal:</label>
                <a href="/admin/sucursal/{{ $pedido->fk_idsucursal }}">
                    <input type="text" id='txtSucursal' class="form-control" value="{{ $pedido->sucursal }}" readonly>
                </a>
            </div>
            <div class="form-group col-lg-6">
                <label>Fecha:</label>
                <input type="datetime-local" id="txtFecha" class="form-control" value="{{ $pedido->fecha }}" readonly>
            </div>
            <div class="form-group col-lg-6">
                <label>Estado:</label>
                <select name="lstEstado" id="lstEstado" class="form-control" required>
                    @foreach ($aEstados as $estado)
                        @if ($estado->idestado == $pedido->fk_idestado)
                        <option value="{{$estado->idestado}}" selected>{{$estado->nombre}}</option>
                        @else
                        <option value="{{$estado->idestado}}">{{$estado->nombre}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-6">
                <label>Total:</label>
                <input type="number" id="txtTotal" class="form-control" value="{{ $pedido->total }}" readonly>
            </div>
            <div class="form-group col-lg-6">
                <label>Pagado:</label><br>
                <select name="lstPagado" id="lstPagado" class="form-control">
                    @if($pedido->pagado)
                    <option value="0">No</option>
                    <option value="1" selected>Sí</option>
                    @else
                    <option value="0" selected>No</option>
                    <option value="1">Sí</option>
                    @endif
                </select>
            </div>
            <div class="form-group col-12">
                <label>Comentarios:</label>
                <textarea id="txtComentarios" class="form-control" style="min-height: 6rem;" readonly>{{ $pedido->comentarios }}</textarea>
            </div>
            <div class="col-12 mt-3">
                <h2>Productos</h2>
                <table class="table table-hover border">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio (al momento del pedido)</th>
                            <th>Imagen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aProductos as $producto)
                        <tr>
                            <td><a href="/admin/producto/{{ $producto->idproducto }}">{{ $producto->nombre }}</a></td>
                            <td>{{ $producto->pivot->cantidad }}</td>
                            <td>{{ $producto->pivot->precio }}</td>
                            <td><img src="/storage/productos/{{ $producto->imagen }}" alt="imagen de producto" width="100"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </form>
</div>
@include('sistema.funciones-form', ['formUrl'=> 'admin/pedido', 'indexUrl'=> '/admin/pedidos'])
@endsection