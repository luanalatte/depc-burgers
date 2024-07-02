@extends('web.plantilla', ['page'=>'contacto'])
@section('contenido')
<section class="layout_padding" id="contact">
  <div class="container">
    <hgroup class="mb-5">
      <h2>Contacto</h2>
    </hgroup>
    <div class="row">
      <div class="col-md-6">
        @include('web.msg')
        <form action="" method="post">
          <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
          <input class="form-control" type="text" name="txtNombre" id="txtNombre" placeholder="Nombre *" required value="{{ $nombre ?? '' }}">
          <input class="form-control" type="email" name="txtEmail" id="txtEmail" placeholder="Correo Electrónico *" required value="{{ $email ?? '' }}">
          <input class="form-control" type="tel" name="txtTelefono" id="txtTelefono" placeholder="Celular / Whatsapp" value="{{ $telefono ?? '' }}">
          <!-- FIXME: Fix textarea height -->
          <textarea class="form-control" name="txtMensaje" id="txtMensaje" placeholder="Escribe tu mensaje aquí *" rows="5" required>{{ $mensaje ?? '' }}</textarea>
          <button type="submit" class="btn btn-primary">ENVIAR</button>
        </form>
      </div>
      <!-- <div class="col-md-6">
        <div class="map_container">
          <div id="googleMap"></div>
        </div>
      </div> -->
    </div>
  </div>
</section>
@endsection