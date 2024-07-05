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
          <form action="" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            @error('txtEmail')
              <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <input class="form-control" type="email" name="txtEmail" id="txtEmail" placeholder="Email" required>
            <div class="text-center"><button type="submit" class="btn btn-primary">Recuperar Clave</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection