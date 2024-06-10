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
                  @if($fila[0]->imagen)
                  <img src="files/{{$fila[0]->imagen}}" class="img-fluid">
                  @endif
                </div>
                <div class="col-9">
                  <h4>{{ $fila[0]->nombre }}</h4>
                  @if($fila[0]->descripcion)
                  <p>{{$fila[0]->descripcion}}</p>
                  @endif
                  <form action="/carrito/editar" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
                    <input type="hidden" name="idproducto" value="{{$fila[0]->idproducto}}">
                    <label for="txtCantidad">Cantidad: </label>
                    <input class="form-control" type="number" name="txtCantidad" id="txtCantidad" value="{{$fila[1]}}">
                    <div class="text-right">
                      <button name="btnEliminar" id="btnEliminar" type="submit" class="btn">Eliminar Producto</button>
                    </div>
                  </form>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection