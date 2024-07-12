@extends('web.plantilla', ['page'=>'nosotros'])
@section('contenido')
  <!-- about section -->

  <section class="about_section layout_padding">
    <div class="container">
      <div class="row">
        <div class="col-md-5  ">
          <div class="img-box">
            <img src="web/images/about-img.png" alt="">
          </div>
        </div>
        <div class="col-md-6 offset-md-1">
          <div class="detail-box">
            @include('web.msg')
            <hgroup class="mb-4">
              <h2>Trabajá con nosotros</h2>
            </hgroup>
            @include('partials.errors')
            <form action="" method="post" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
              <input class="form-control" type="text" name="txtNombre" id="txtNombre" placeholder="Nombre *" value="{{ old('txtNombre') }}" required>
              <input class="form-control" type="text" name="txtApellido" id="txtApellido" placeholder="Apellido *" value="{{ old('txtApellido') }}" required>
              <input class="form-control" type="email" name="txtEmail" id="txtEmail" placeholder="Correo Electrónico *" value="{{ old('txtEmail') }}" required>
              <input class="form-control" type="text" name="txtDomicilio" id="txtDomicilio" placeholder="Domicilio *" value="{{ old('txtDomicilio') }}" required>
              <input class="form-control" type="tel" name="txtTelefono" id="txtTelefono" placeholder="Celular / Whatsapp *" value="{{ old('txtTelefono') }}" required>
              <label for="fileCV">Adjuntar Currículum:</label>
              <input class="form-control-file mb-3" type="file" name="fileCV" id="fileCV" accept=".pdf, .doc, .docx" required>
              <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end about section -->
@endsection