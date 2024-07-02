@extends('web.plantilla', ['page'=>'micuenta'])
@section('contenido')
<section class="layout_padding" id="micuenta">
  <div class="container">
    @if(count($aPedidos) > 0)
      <hgroup class="mb-5">
        <h2>Pedidos Activos</h2>
      </hgroup>
      <table class="table border mb-5">
        <thead>
          <tr>
            <th>Pedido</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Pagado</th>
            <th>Sucursal</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($aPedidos as $pedido)
          <tr>
            <td>{{ $pedido->idpedido }}</td>
            <td>{{ $pedido->total }}</td>
            <td>{{ $pedido->estado }}</td>
            <td>{{ $pedido->pagado ? "Sí" : "No" }}</td>
            <td>{{ $pedido->sucursal }}</td>
            @if(!$pedido->pagado)
            <td>
              <a href="/mercadopago/pagar/{{ $pedido->idpedido }}" class="btn btn-primary">Pagar con Mercado Pago</a>
            </td>
            @else
            <td></td>
            @endif
          </tr>
          @endforeach
        </tbody>
      </table>
    @endif
    <hgroup class="mb-5">
      <h2>Mi Cuenta</h2>
    </hgroup>
    @include('web.msg')
    <form action="" method="POST" class="mb-3">
      <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
      <div class="row">
        <div class="col-md-6">
          <label for="txtNombre">Nombre:</label>
          <input type="text" name="txtNombre" id="txtNombre" class="form-control" required value="{{ $cliente->nombre }}"></input>
        </div>
        <div class="col-md-6">
          <label for="txtApellido">Apellido:</label>
          <input type="text" name="txtApellido" id="txtApellido" class="form-control" required value="{{ $cliente->apellido }}"></input>
        </div>
        <div class="col-md-6">
          <label for="txtDocumento">Documento:</label>
          <input type="text" name="txtDocumento" id="txtDocumento" class="form-control" required value="{{ $cliente->dni }}"></input>
        </div>
        <div class="col-md-6">
          <label for="txtEmail">Correo Electrónico:</label>
          <input type="email" name="txtEmail" id="txtEmail" class="form-control" required value="{{ $cliente->email }}"></input>
        </div>
        <div class="col-md-6">
          <label for="txtTelefono">Celular / Whatsapp:</label>
          <input type="tel" name="txtTelefono" id="txtTelefono" class="form-control" value="{{ $cliente->telefono }}"></input>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary mr-2">Guardar</button>
          <a href="/logout" class="btn btn-secondary">Cerrar Sesión</a>
        </div>
      </div>
    </form>
    <a href="/cambiar-clave">Cambiar contraseña</a>
  </div>
</section>
@endsection