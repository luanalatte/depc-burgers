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
@include('sistema.msg')
<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        <a href="#" onclick="javascript: filtrarEstado(null);" class="btn btn-primary">Todos ({{ $countPedidos }})</a>
        @foreach($aEstados as $estado)
        <a href="#" onclick="javascript: filtrarEstado({{ $estado->idestado }});" class="btn btn-{{ $estado->color ?? 'primary' }}">{{ $estado->nombre }} ({{ $estado->count }})</a>
        @endforeach
    </div>
    <div class="d-flex align-items-center">
        <label for="" class="flex-shrink-0">Filtrar por período:</label>
        <input class="form-control mx-2" type="datetime-local" id="txtDateFrom">
        -
        <input class="form-control mx-2" type="datetime-local" id="txtDateTo">
    </div>
    <div class="d-flex align-items-center">
        <label for="lstSucursales" class="flex-shrink-0">Filtrar por sucursal:</label>
        <select id="lstSucursales" class="form-control mx-2" onchange="javascript: filtrarSucursal();">
            <option value="0">Todas</option>
            @foreach($aSucursales as $sucursal)
                <option value="{{ $sucursal->idsucursal }}">{{ $sucursal->nombre }}</option>
            @endforeach
        </select>
    </div>
</div>

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
    var filtroEstado = null;
    var filtroSucursal = null;
	var dataTable = $('#grilla').DataTable({
	    "processing": true,
        "serverSide": true,
	    "bFilter": true,
	    "bInfo": true,
	    "bSearchable": true,
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "ajax": {
            url: "{{ route('pedidos.cargarGrilla') }}",
            data: function(d) {
                d.estado = filtroEstado;
                d.sucursal = filtroSucursal;
            }
        }
	});

    function filtrarEstado(estado)
    {
        filtroEstado = estado;
        dataTable.ajax.reload();
    }

    function filtrarSucursal()
    {
        filtroSucursal = $('#lstSucursales').val();
        dataTable.ajax.reload();
    }
</script>
@endsection