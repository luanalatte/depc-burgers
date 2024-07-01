@extends('sistema.plantilla')
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
<div class="toolbar mb-3 d-flex justify-content-between align-items-center">
    <div class="d-flex flex-wrap justify-content-center">
        <a href="#" onclick="javascript: filtrarEstado(0);" class="m-1 btn btn-primary">Todos ({{ $countPedidos }})</a>
        @foreach($aEstados as $estado)
        <a href="#" onclick="javascript: filtrarEstado({{ $estado->idestado }});" class="m-1 btn btn-{{ $estado->color ?? 'primary' }}">{{ $estado->nombre }} ({{ $estado->pedidos_count }})</a>
        @endforeach
    </div>
    <div class="d-flex flex-wrap justify-content-between">
        <div class="mx-3">
            <label class="flex-shrink-0">Filtrar por período:</label>
            <div class="d-flex">
                <input class="form-control mr-2" type="date" id="txtFechaDesde" onchange="javascript: filtrarFecha();">
                <input class="form-control" type="date" id="txtFechaHasta" onchange="javascript: filtrarFecha();">
            </div>
        </div>
        <div class="">
            <label for="lstSucursales">Filtrar por sucursal:</label>
            <select id="lstSucursales" class="form-control" onchange="javascript: filtrarSucursal();">
                <option value="0">Todas</option>
                @foreach($aSucursales as $sucursal)
                    <option value="{{ $sucursal->idsucursal }}">{{ $sucursal->nombre }}</option>
                @endforeach
            </select>
        </div>
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
            <th>Método de Pago</th>
            <th>Pagado</th>
            <th>Total</th>
        </tr>
    </thead>
</table> 
<script>
    function setEstado(idpedido)
    {
        let val = $('#lstEstado-id' + idpedido).val();

        $.ajax({
            type: "GET",
            url: "{{ route('pedidos.setEstado') }}",
            data: { id:idpedido, estado:val },
            async: true,
            dataType: "json",
            success: function (data) {
                msgShow(data.msg, data.err == 0 ? "success" : "danger");
            }
        });
    }

    var filtroEstado = 0;
    var filtroSucursal = 0;
    var filtroFechaDesde = null;
    var filtroFechaHasta = null;
	var dataTable = $('#grilla').DataTable({
	    "processing": true,
        "serverSide": true,
	    "bFilter": true,
	    "bInfo": true,
	    "bSearchable": true,
        "pageLength": 25,
        columns: [
            { searchable: false, orderable: false, width: "0" },
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        ],
        "order": [[ 5, "desc" ]],
        "ajax": {
            url: "{{ route('pedidos.cargarGrilla') }}",
            data: function(d) {
                d.estado = filtroEstado;
                d.sucursal = filtroSucursal;
                d.fechaDesde = filtroFechaDesde;
                d.fechaHasta = filtroFechaHasta;
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

    function filtrarFecha()
    {
        filtroFechaDesde = $('#txtFechaDesde').val();
        filtroFechaHasta = $('#txtFechaHasta').val();
        dataTable.ajax.reload();
    }
</script>
@endsection