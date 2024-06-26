@extends('web.plantilla', ['page'=>'register'])
@section('contenido')
<section class="layout_padding vh-100" id="register">
  <div class="container">
    <hgroup class="mb-4">
      <h2>Crear Cuenta</h2>
    </hgroup>
    @include('web.msg')
    <form action="" method="post" class="mb-3">
      <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
      <div class="row">
        <div class="col-md-6">
          <input class="form-control" type="text" name="txtNombre" id="txtNombre" placeholder="Nombre(s) *" required>
        </div>
        <div class="col-md-6">
          <input class="form-control" type="text" name="txtApellido" id="txtApellido" placeholder="Apellido(s) *" required>
        </div>
        <div class="col-md-6">
          <input class="form-control" type="text" name="txtDNI" id="txtDNI" placeholder="Documento *" required>
        </div>
        <div class="col-md-6">
          <input class="form-control" type="email" name="txtEmail" id="txtEmail" placeholder="Email *" required>
        </div>
        <div class="col-md-6">
          <input class="form-control" type="password" name="txtClave" id="txtClave" placeholder="Contraseña *" required>
        </div>
        <div class="col-md-6">
          <input class="form-control" type="password" name="txtClave2" id="txtClave2" placeholder="Repetir Contraseña *" required>
        </div>
        <div class="col-md-6">
          <input class="form-control" type="tel" name="txtTelefono" id="txtTelefono" placeholder="Teléfono (opcional)">
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Crear Cuenta</button>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection