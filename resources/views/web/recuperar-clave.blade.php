@extends('web.plantilla', ['page'=>'recuperar-clave'])
@section('slider')
<section class="slider layout_padding text-white vh-100" id="recuperar-clave">
  <div class="container">
    <div class="row">
      <div class="col-md-8 offset-md-2 col-lg-4 offset-lg-0">
        <div class="detail-box">
          <hgroup class="mb-4">
            <h2>Recuperar Clave</h2>
          </hgroup>
          @include('web.msg')
          <form action="" method="post" class="text-center text-white">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            <input class="form-control" type="email" name="txtEmail" id="txtEmail" placeholder="Email" required>
            <button type="submit" class="btn btn-primary">Recuperar Clave</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection