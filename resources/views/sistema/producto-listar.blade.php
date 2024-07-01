@extends('sistema.plantilla')
@section('titulo', "Lista de Productos")
@section('scripts')
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/datatables.min.js') }}"></script>
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
        <li class="breadcrumb-item active">Productos</a></li>
    </ol>
    <ol class="toolbar">
        <li class="btn-item"><a title="Nuevo" href="/admin/producto/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
        <li class="btn-item"><a title="Recargar" href="#" class="fa fa-sync" aria-hidden="true" onclick='window.location.replace("/admin/productos");'><span>Recargar</span></a></li>
    </ol>
@endsection
@section('contenido')
@include('sistema.msg')
<table id="grilla" class="display">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Cantidad en Stock</th>
            <th>Precio</th>
            <th>Descripcion</th>
            <th>Imagen</th>
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
        columns: [
            null,
            null,
            null,
            null,
            null,
            { searchable: false, orderable: false }
        ],
        "order": [[ 0, "asc" ]],
	    "ajax": "{{ route('productos.cargarGrilla') }}"
	});
</script>
@endsection