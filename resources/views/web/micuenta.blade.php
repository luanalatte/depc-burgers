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
          <input type="text" readonly class="form-control" value="{{ $cliente->nombre }}"></input>
          <label for="txtApellido">Apellido:</label>
          <input type="text" readonly class="form-control" value="{{ $cliente->apellido }}"></input>
          <label for="txtDocumento">Documento:</label>
          <input type="text" readonly class="form-control" value="{{ $cliente->dni }}"></input>
          <label for="txtEmail">Correo Electrónico:</label>
          <input type="text" readonly class="form-control" value="{{ $cliente->email }}"></input>
          <label for="txtTelefono">Celular / Whatsapp:</label>
          <input type="text" readonly class="form-control" value="{{ $cliente->telefono }}"></input>
        </form>
        <a href="/logout" class="btn btn-secondary">Cerrar Sesión</a>
      </div>
    </div>
  </div>
</section>
@endsection