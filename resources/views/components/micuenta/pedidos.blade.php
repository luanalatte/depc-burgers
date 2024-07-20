@props(['pedidos'])
<table class="table border">
  <thead>
    <tr>
      <th>Pedido</th>
      <th>Total</th>
      <th>Estado</th>
      <th>Pagado</th>
      <th>Sucursal</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach($pedidos as $pedido)
      <tr>
        <td>{{ $pedido->idpedido }}</td>
        <td>{{ $pedido->total }}</td>
        <td>{{ $pedido->estado }}</td>
        <td>{{ $pedido->pagado ? "Sí" : "No" }}</td>
        <td>{{ $pedido->sucursal }}</td>
        <td>
          @if(!$pedido->pagado)
            <a href="/mercadopago/pagar/{{ $pedido->idpedido }}" class="btn btn-primary">Pagar con Mercado Pago</a>
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>