@extends('web.plantilla', ['page'=>'micuenta'])
@section('contenido')
<section class="layout_padding" id="micuenta">
  <div class="container">
    <div class="mb-5 d-flex flex-column gap-3">
      @if(count($cliente->pedidosActivos) > 0)
        <div>
          <hgroup class="mb-3">
            <h2>Pedidos Activos</h2>
          </hgroup>
          <x-micuenta.pedidos :pedidos="$cliente->pedidosActivos" />
        </div>
      @endif
      @if(count($cliente->historialDePedidos) > 0)
        <div>
          <hgroup class="mb-3">
            <h2>Historial De Pedidos</h2>
          </hgroup>
          <x-micuenta.pedidos :pedidos="$cliente->historialDePedidos" />
        </div>
      @endif
    </div>
    <hgroup class="mb-5">
      <h2>Mi Cuenta</h2>
    </hgroup>
    @include('web.msg')
    @include('partials.errors')
    <form action="" method="POST" class="mb-3">
      <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
      <div class="row">
        <div class="col-md-6">
          <label for="txtNombre">Nombre:</label>
          <input type="text" name="txtNombre" id="txtNombre" class="form-control" required value="{{ old('txtNombre') ?? $cliente->nombre }}"></input>
        </div>
        <div class="col-md-6">
          <label for="txtApellido">Apellido:</label>
          <input type="text" name="txtApellido" id="txtApellido" class="form-control" required value="{{ old('txtApellido') ?? $cliente->apellido }}"></input>
        </div>
        <div class="col-md-6">
          <label for="txtDocumento">Documento:</label>
          <input type="text" name="txtDocumento" id="txtDocumento" class="form-control" required value="{{ old('txtDocumento') ?? $cliente->dni }}"></input>
        </div>
        <div class="col-md-6">
          <label for="txtEmail">Correo Electrónico:</label>
          <input type="email" name="txtEmail" id="txtEmail" class="form-control" required value="{{ old('txtEmail') ?? $cliente->email }}"></input>
        </div>
        <div class="col-md-6">
          <label for="txtTelefono">Celular / Whatsapp:</label>
          <input type="tel" name="txtTelefono" id="txtTelefono" class="form-control" value="{{ old('txtTelefono') ?? $cliente->telefono }}"></input>
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