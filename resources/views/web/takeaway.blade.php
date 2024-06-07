@extends('web.plantilla', ['page'=>'takeaway'])
@section('contenido')
<!-- food section -->

<section class="food_section layout_padding">
    <div class="container">
      <hgroup class="text-center">
        <h2>Take Away</h2>
      </hgroup>

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
              <article class="box product">
                <div>
                  <div class="img-box">
                    <img src="files/{{$producto->imagen}}" alt="Imagen">
                  </div>
                  <div class="detail-box">
                    <h5>{{$producto->nombre}}</h5>
                    @if($producto->descripcion)
                    <p>{{$producto->descripcion}}</p>
                    @endif
                    <div class="d-flex justify-content-between mt-auto">
                      <h6>${{ number_format($producto->precio, 2, ",", ".") }}</h6>
                      <!-- TODO: Solo mostrar el botón si se ha iniciado sesión -->
                      <form action="" method="POST" class="add-to-cart">
                        <input type="number" name="txtCantidad" id="txtCantidad" value="1">
                        <a href="">
                          Añadir al carrito <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        </a>
                      </form>
                    </div>
                  </div>
                </div>
              </article>
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