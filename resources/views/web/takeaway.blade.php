@extends('web.plantilla', ['page'=>'takeaway'])
@section('contenido')
<!-- food section -->

<section class="food_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Take Away</h2>
      </div>

      <ul class="filters_menu">
        <li class="active" data-filter="*">Todos</li>
        @foreach($aCategorias as $categoria)
        <li data-filter=".categoria-{{$categoria->idcategoria}}">{{$categoria->nombre}}</li>
        @endforeach
      </ul>

      <div class="filters-content">
        <div class="row grid">
          @foreach($aProductos as $producto)
            <div class="col-sm-6 col-lg-4 all categoria-{{$producto->fk_idcategoria}}">
              <div class="box">
                <div>
                  <div class="img-box">
                    <img src="files/{{$producto->imagen}}" alt="Imagen">
                  </div>
                  <div class="detail-box">
                    <h5>{{$producto->nombre}}</h5>
                    @if($producto->descripcion)
                    <p>{{$producto->descripcion}}</p>
                    @endif
                    <div class="options">
                      <h6>${{ number_format($producto->precio, 2, ",", ".") }}</h6>
                      <a href="">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <!-- <div class="btn-box">
        <a href="">View More</a>
      </div> -->
    </div>
  </section>

  <!-- end food section -->
@endsection