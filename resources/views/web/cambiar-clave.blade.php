@extends('web.plantilla', ['page'=>'cambiar-clave'])
@section('contenido')
<section class="layout_padding" id="cambiar-clave">
  <div class="container">
    <hgroup class="mb-5">
      <h2>Cambiar Contraseña</h2>
    </hgroup>
    @include('web.msg')
    @include('partials.errors')
    <form action="" method="POST" class="mb-3">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="row">
        <div class="col-12">
          <input type="password" name="txtClaveAntigua" id="txtClaveAntigua" class="form-control" placeholder="Contraseña Actual *" required>
        </div>
        <div class="col-md-6">
          <input type="password" name="txtClave" id="txtClave" class="form-control" placeholder="Contraseña Nueva *" required>
        </div>
        <div class="col-md-6">
          <input type="password" name="txtClave_confirmation" id="txtClave_confirmation" class="form-control" placeholder="Repetir Contraseña Nueva *" required>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary mr-2">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection