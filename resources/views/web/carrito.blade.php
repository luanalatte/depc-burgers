@extends('web.plantilla', ['page'=>'carrito'])
@section('contenido')
<section class="layout_padding" id="carrito">
  <div class="container">
    <hgroup class="mb-5 text-center">
      <h2>Carrito de compras</h2>
    </hgroup>
    <div class="row">
      <div class="col-md-6 offset-md-3">
        @if(Session::has('msg'))
        <div id="msg" class="mb-5 alert alert-{{Session::get('msg')['ESTADO']}}">{{ Session::get('msg')['MSG'] }}</div>
        @endif
      </div>
      <div class="col-md-6 offset-md-3">
        @if(empty($carrito->productos))
          <p class="text-center">Tu carrito está vacío. Comienza a <a href="/takeaway">Agregar Productos</a> para ordenar.</p>
        @else
          <div class="d-flex flex-column">
            @foreach($carrito->productos as $producto)
              <div class="border py-3 px-1 row">
                <div class="col-3">
                  @if($producto->imagen)
                  <img src="files/{{ $producto->imagen }}" class="img-fluid">
                  @endif
                </div>
                <div class="col-9">
                  <h4>{{ $producto->nombre }} <small class="font-weight-light">{{ $producto->cantidad }}</small></h4>
                  @if($producto->descripcion)
                  <p>{{ $producto->descripcion }}</p>
                  @endif
                  <form action="/carrito/editar" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                    <input type="hidden" name="idproducto" value="{{ $producto->idproducto }}">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Cantidad:</span>
                      </div>
                      <input class="form-control" type="number" name="txtCantidad" id="txtCantidad" value="{{ $producto->pivot->cantidad }}">
                    </div>
                    <div class="my-2 d-flex justify-content-between">
                      <span class="d-block text-right">$ {{ number_format($producto->precio, 2, ',', '.') }}</span>
                      <span class="d-block text-right">SUBTOTAL $ {{ number_format($producto->precio * $producto->pivot->cantidad, 2, ',', '.') }}</span>
                    </div>
                    <div class="text-right">
                      <button name="btnEliminar" id="btnEliminar" type="submit" class="btn">Eliminar Producto</button>
                    </div>
                  </form>
                </div>
              </div>
            @endforeach
          </div>
          <div class="mt-4">
            <form action="/carrito/confirmar" method="post">
              <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
              <div>
                <strong>TOTAL $ {{ number_format($carrito->total, 2, ',', '.') }}</strong>
              </div>
              <div class="row">
                <div class="col-12 mt-3">
                  <label for="txtComentarios">Comentarios:</label>
                  <textarea class="form-control" name="txtComentarios" id="txtComentarios"></textarea>
                </div>
                <div class="col-12 mt-3 d-flex align-items-center">
                  <select class="form-control w-100" name="lstSucursal" id="lstSucursal" required>
                    <option value="" selected disabled>Seleccionar sucursal de retiro</option>
                    @foreach($aSucursales as $sucursal)
                    <option value="{{ $sucursal->idsucursal }}">{{ $sucursal->nombre }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-lg-6 mt-3 d-flex align-items-center">
                  <select class="form-control w-100" name="lstMedioDePago" id="lstMedioDePago" required>
                    <option value="" selected disabled>Seleccionar Medio De Pago</option>
                    <option value="mercadopago">Mercadopago</option>
                    <option value="sucursal">Abonar en sucursal</option>
                  </select>
                </div>
                <div class="col-lg-6 mt-3">
                  <button type="submit" class="d-block btn btn-primary w-100">CONTINUAR</button>
                </div>
              </div>
            </form>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection