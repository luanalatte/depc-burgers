@extends('web.plantilla', ['page'=>'takeaway'])
@section('contenido')
<!-- food section -->

<section class="food_section layout_padding">
    <div class="container">
      <hgroup class="text-center">
        <h2>Take Away</h2>
      </hgroup>
      <div id="msg"></div>
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
                  @if ($producto->imagen)
                    <img src="/storage/productos/{{ $producto->imagen }}" alt="{{ $producto->nombre }}">
                  @else
                    <p class="text-dark">Producto sin imagen</p>
                  @endif
                  </div>
                  <div class="detail-box">
                    <h5>{{$producto->nombre}}</h5>
                    @if($producto->descripcion)
                    <p>{{$producto->descripcion}}</p>
                    @endif
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                      <h6 class="price my-0 mr-2 font-weight-bold">${{ number_format($producto->precio, 2, ",", ".") }}</h6>
                      @if($producto->cantidad > 0)
                        @if(Session::get('cliente_id'))
                        <div class="input-group justify-content-end flex-nowrap">
                          <div class="input-group-prepend">
                            <button class="btn btn-primary py-0 subtract-button">
                              <i class="fa fa-minus" aria-hidden="true"></i>
                            </button>
                          </div>
                          <input type="text" class="form-control" readonly data-idproducto="{{ $producto->idproducto }}" name="txtCantidad" id="txtCantidad" value="{{ $producto->nCarrito ?? 0 }}">
                          <div class="input-group-append">
                            <button class="btn btn-primary py-0 add-button">
                              <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                          </div>
                        </div>
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
<script>
  function editarCarrito(idproducto, cantidad)
  {
    $.ajax({
      type: "POST",
      url: "{{ route('carrito.editar') }}",
      data: { _token: "{{ csrf_token() }}", idproducto:idproducto, cantidad:cantidad },
      async: true,
      dataType: "json",
      success: function (data) {
        $('#nCarrito').html(data.nCarrito ? data.nCarrito : "");
      }
    });
  }

  document.querySelectorAll('.product').forEach(producto => {
    let input = producto.querySelector('input#txtCantidad')

    producto.querySelector('.subtract-button').addEventListener('click', () => {
      input.value = Math.max(parseInt(input.value) - 1, 0);
      editarCarrito(input.dataset.idproducto, input.value);
    });
    
    producto.querySelector('.add-button').addEventListener('click', () => {
      input.value = parseInt(input.value) + 1;
      editarCarrito(input.dataset.idproducto, input.value);
    });
  });
</script>
@endsection