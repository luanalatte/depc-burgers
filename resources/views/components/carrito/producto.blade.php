@props(['producto'])
<div class="my-3 row product" id="carritoProducto-{{ $producto->idproducto }}" data-precio="{{ $producto->precio }}">
  <div class="col-3">
    @if($producto->imagen)
      <img src="/storage/productos/{{ $producto->imagen }}" class="img-fluid">
    @endif
  </div>
  <div class="col-9">
    <h4>{{ $producto->nombre }}</h4>
    @if($producto->descripcion)
      <p>{{ $producto->descripcion }}</p>
    @endif
    <div class="input-group justify-content-end flex-nowrap">
      <div class="input-group-prepend">
        <button class="btn btn-primary py-0 subtract-button">
          <i class="fa fa-minus" aria-hidden="true"></i>
        </button>
      </div>
      <input type="text" class="form-control" readonly data-idproducto="{{ $producto->idproducto }}" name="txtCantidad" id="txtCantidad" value="{{ $producto->pivot->cantidad }}">
      <div class="input-group-append">
        <button class="btn btn-primary py-0 add-button">
          <i class="fa fa-plus" aria-hidden="true"></i>
        </button>
      </div>
    </div>
    <div class="my-2 d-flex justify-content-between">
      <span class="d-block text-right">$ <span class="precio">{{ number_format($producto->precio, 2, ',', '.') }}</span></span>
      <span class="d-block text-right">SUBTOTAL $ <span class="subtotal">{{ number_format($producto->precio * $producto->pivot->cantidad, 2, ',', '.') }}</span></span>
    </div>
  </div>
</div>