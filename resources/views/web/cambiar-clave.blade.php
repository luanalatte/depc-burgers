@extends('web.plantilla', ['page'=>'cambiar-clave'])
@section('contenido')
<section class="layout_padding" id="cambiar-clave">
  <div class="container">
    <hgroup class="mb-5">
      <h2>Cambiar Contraseña</h2>
    </hgroup>
    @include('web.msg')
    <form action="" method="POST" class="mb-3">
      <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
      <div class="row">
        <div class="col-12">
          <label for="txtClaveAntigua">Clave Antigua: *</label>
          <input type="password" name="txtClaveAntigua" id="txtClaveAntigua" class="form-control"></input>
        </div>
        <div class="col-md-6">
          <label for="txtClave1">Clave Nueva: *</label>
          <input type="password" name="txtClave1" id="txtClave1" class="form-control"></input>
        </div>
        <div class="col-md-6">
          <label for="txtClave2">Repetir Clave Nueva: *</label>
          <input type="password" name="txtClave2" id="txtClave2" class="form-control"></input>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary mr-2">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection