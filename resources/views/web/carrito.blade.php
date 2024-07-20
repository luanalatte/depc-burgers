@extends('web.plantilla', ['page'=>'carrito'])
@section('contenido')
<section class="layout_padding" id="carrito">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <hgroup class="mb-5 text-center">
          <h2>Carrito de compras</h2>
        </hgroup>
        @if(Session::has('msg'))
          <div id="msg" class="mb-5 alert alert-{{Session::get('msg')['ESTADO']}}">{{ Session::get('msg')['MSG'] }}</div>
        @endif
        @if($carrito->nProductos === 0)
          <p class="text-center">Tu carrito está vacío. Comienza a <a href="/takeaway">Agregar Productos</a> para ordenar.</p>
        @else
          <div class="d-flex flex-column">
            @foreach($carrito->productos as $producto)
              <x-carrito.producto :producto="$producto" />
            @endforeach
          </div>
        @endif
      </div>
      <div class="col-md-6">
        <hgroup class="mb-5 text-center">
          <h2>Pagar</h2>
        </hgroup>
        <form action="/carrito/confirmar" method="post">
          <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
          <div>
            <strong>TOTAL $ <span id="total">{{ number_format($carrito->total, 2, ',', '.') }}</span></strong>
          </div>
          <div class="row">
            <div class="col-12 mt-3">
              <label for="txtComentarios">Comentarios:</label>
              <textarea class="form-control" name="txtComentarios" id="txtComentarios" rows="5"></textarea>
            </div>
            <div class="col-12 d-flex align-items-center">
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
    </div>
  </div>
</section>
<script>
  function calcularCarrito()
  {
    let formatter = Intl.NumberFormat('es-ES', { minimumFractionDigits: 2 });

    let fTotal = 0;
    let nProductos = 0;

    document.querySelectorAll('.product').forEach(producto => {
      let fPrecio = parseFloat(producto.dataset.precio);
      let fCantidad = parseFloat(producto.querySelector('#txtCantidad').value);
      let fSubtotal = fPrecio * fCantidad;

      producto.querySelector('.subtotal').innerHTML = formatter.format(fSubtotal);
      fTotal += fSubtotal;
      nProductos++;
    });

    document.querySelector('#total').innerHTML = formatter.format(fTotal);

    if (nProductos < 1) {
      window.location.reload();
    } else {
      $('#nCarrito').html(nProductos);
    }
  }

  function editarCarrito(idproducto, cantidad, redirect=true)
  {
    $.ajax({
      type: "POST",
      url: "{{ route('carrito.editar') }}",
      data: { _token: "{{ csrf_token() }}", idproducto:idproducto, cantidad:cantidad },
      async: true,
      dataType: "json",
      success: function (data) {
        if (cantidad == 0) {
          $('#carritoProducto-' + idproducto).remove();
        }
        calcularCarrito();
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