@extends('web.plantilla', ['page'=>$page])
@section('contenido')
<section class="layout_padding">
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3 text-center">
        <hgroup class="mb-3">
          <h2>{{ $titulo }}</h2>
        </hgroup>
        <p>{{ $mensaje }}</p>
      </div>
    </div>
  </div>
</section>
@endsection