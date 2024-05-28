@extends('plantilla')
@section('titulo', "Lista de Pedidos")
@section('scripts')
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/datatables.min.js') }}"></script>
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
        <li class="breadcrumb-item active">Pedidos</a></li>
    </ol>
    <ol class="toolbar">
        <li class="btn-item"><a title="Nuevo" href="/admin/pedido/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
        <li class="btn-item"><a title="Recargar" href="#" class="fa fa-sync" aria-hidden="true" onclick='window.location.replace("/admin/pedidos");'><span>Recargar</span></a></li>
    </ol>
@endsection
@section('contenido')
@if(isset($msg))
    <div id="msg"></div>
    <script>msgShow('{{ $msg["MSG"] }}', '{{ $msg["ESTADO"] }}')</script>
@endif
<table id="grilla" class="display">
    <thead>
        <tr>
            <th></th>
            <th>Nº</th>
            <th>Cliente</th>
            <th>Sucursal</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Total</th>
        </tr>
    </thead>
</table> 
<script>
	var dataTable = $('#grilla').DataTable({
	    "processing": true,
        "serverSide": true,
	    "bFilter": true,
	    "bInfo": true,
	    "bSearchable": true,
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
	    "ajax": "{{ route('pedidos.cargarGrilla') }}"
	});
</script>
@endsection