@extends('web.plantilla', ['page'=>'carrito'])
@section('contenido')
<section class="layout_padding" id="carrito">
  <div class="container">
    <hgroup class="mb-5 text-center">
      <h2>Carrito de compras</h2>
    </hgroup>
    <div class="row">
      <div class="col-md-6 offset-md-3">
        @if(!isset($carrito) || !$carrito->aProductos)
          <p class="text-center">Tu carrito está vacío. Comienza a <a href="/takeaway">Agregar Productos</a> para ordenar.</p>
        @else
          <div class="d-flex flex-column">
            @foreach($carrito->aProductos as $fila)
              <div class="border py-3 px-1 row">
                <div class="col-3">
                  @if($fila['producto']->imagen)
                  <img src="files/{{ $fila['producto']->imagen }}" class="img-fluid">
                  @endif
                </div>
                <div class="col-9">
                  <h4>{{ $fila['producto']->nombre }}</h4>
                  @if($fila['producto']->descripcion)
                  <p>{{ $fila['producto']->descripcion }}</p>
                  @endif
                  <form action="/carrito/editar" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                    <input type="hidden" name="idproducto" value="{{ $fila['producto']->idproducto }}">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Cantidad:</span>
                      </div>
                      <input class="form-control" type="number" name="txtCantidad" id="txtCantidad" value="{{ $fila['cantidad'] }}">
                    </div>
                    <div class="my-2">
                      <span class="d-block text-right">SUBTOTAL $ {{ number_format($fila['subtotal'], 2, ',', '.') }}</span>
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
              <div>
                <strong>TOTAL $ {{ number_format($carrito->total, 2, ',', '.') }}</strong>
              </div>
              <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center">
                <select name="lstOpcionPago" id="lstOpcionPago" required>
                  <option value="" selected disabled>Seleccionar Medio De Pago</option>
                  <option value="0">Mercadopago</option>
                  <option value="1">Abonar en sucursal</option>
                </select>
                <button name="btnPagar" id="btnPagar" type="submit" class="btn btn-primary my-3">CONTINUAR AL PAGO</button>
              </div>
            </form>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection