@extends('web.plantilla', ['page'=>'login'])
@section('slider')
<section class="slider layout_padding text-white vh-100" id="login">
  <div class="container">
    <div class="row">
      <div class="col-md-8 offset-md-2 col-lg-4 offset-lg-0">
        <div class="detail-box">
          <hgroup class="mb-4">
            <h2>Iniciar sesión</h2>
          </hgroup>
          @include('web.msg')
          <form action="" method="post" class="text-center mb-3 text-white">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            <input class="form-control" type="email" name="txtEmail" id="txtEmail" placeholder="Email">
            <input class="form-control" type="password" name="txtClave" id="txtClave" placeholder="Contraseña">
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
          </form>
          <div class="d-flex align-items-center justify-content-center">
            <p class="my-0 mr-2">¿Aún no tienes una cuenta?</p>
            <a href="/register" class="btn btn-secondary py-1 px-3 m-0">Regístrate ahora</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection