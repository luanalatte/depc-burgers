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
            <div class="col-md-6 col-lg-4 all categoria-{{$producto->fk_idcategoria}}">
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
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                      <h6 class="price my-0 mr-2 font-weight-bold">${{ number_format($producto->precio, 2, ",", ".") }}</h6>
                      @if($producto->cantidad > 1)
                        @if(Session::get('cliente_id'))
                        <form action="/carrito/editar" method="POST">
                          <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                          <input type="hidden" name="idproducto" value="{{$producto->idproducto}}">
                          <div class="input-group justify-content-end flex-nowrap">
                            <input type="number" class="form-control" name="txtCantidad" id="txtCantidad" value="1">
                            <div class="input-group-append">
                              <button type="submit" class="btn btn-primary py-0"><i class="fa fa-shopping-cart" aria-hidden="true"></i></button>
                            </div>
                          </div>
                        </form>
                        @else
                        <a href="/login" class="btn btn-primary">Pedir Ahora</a>
                        @endif
                      @else
                        <div class="btn disabled">No disponible</div>
                      @endif
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