@extends('web.plantilla', ['page'=>'micuenta'])
@section('contenido')
<section class="layout_padding" id="micuenta">
  <div class="container">
    <hgroup class="mb-5 text-center">
      <h2>Mi Cuenta</h2>
    </hgroup>
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <form action="" method="POST">
          <label for="txtNombre">Nombre:</label>
          <span class="form-control">{{ $cliente->nombre }}</span>
          <label for="txtApellido">Apellido:</label>
          <span class="form-control">{{ $cliente->apellido }}</span>
          <label for="txtDocumento">Documento:</label>
          <span class="form-control">{{ $cliente->dni }}</span>
          <label for="txtEmail">Correo Electrónico:</label>
          <span class="form-control">{{ $cliente->email }}</span>
          <label for="txtTelefono">Celular / Whatsapp:</label>
          <span class="form-control">{{ $cliente->telefono }}</span>
        </form>
        <a href="/logout" class="btn btn-secondary">Cerrar Sesión</a>
      </div>
    </div>
  </div>
</section>
@endsection