@extends('web.plantilla', ['page'=>'micuenta'])
@section('contenido')
<section class="layout_padding" id="micuenta">
  <div class="container">
    <div class="row">
      <div class="mb-5 col-md-6 order-md-2 mb-md-0">
        <hgroup class="mb-5">
          <h2>Pedidos Activos</h2>
        </hgroup>
        <table class="table border">
          <thead>
            <tr>
              <th>Pedido</th>
              <th>Total</th>
              <th>Estado</th>
              <th>Sucursal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($aPedidos as $pedido)
            <tr>
              <td>{{ $pedido->idpedido }}</td>
              <td>{{ $pedido->total }}</td>
              <td>{{ $pedido->estado }}</td>
              <td>{{ $pedido->sucursal }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="col-md-6">
        <hgroup class="mb-5">
          <h2>Mi Cuenta</h2>
        </hgroup>
        @include('web.msg')
        <form action="" method="POST">
          <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
          <label for="txtNombre">Nombre:</label>
          <input type="text" readonly class="form-control" value="{{ $cliente->nombre }}"></input>
          <label for="txtApellido">Apellido:</label>
          <input type="text" readonly class="form-control" value="{{ $cliente->apellido }}"></input>
          <label for="txtDocumento">Documento:</label>
          <input type="text" readonly class="form-control" value="{{ $cliente->dni }}"></input>
          <label for="txtEmail">Correo Electrónico:</label>
          <input type="text" readonly class="form-control" value="{{ $cliente->email }}"></input>
          <label for="txtTelefono">Celular / Whatsapp:</label>
          <input type="text" readonly class="form-control" value="{{ $cliente->telefono }}"></input>
          <button type="submit" class="btn btn-primary mr-2">Guardar</button>
          <a href="/logout" class="btn btn-secondary">Cerrar Sesión</a>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection