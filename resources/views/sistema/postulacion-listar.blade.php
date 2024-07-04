@extends('sistema.plantilla')
@section('titulo', "Lista de Postulaciones")
@section('scripts')
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/datatables.min.js') }}"></script>
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
        <li class="breadcrumb-item active">Postulaciones</a></li>
    </ol>
    <ol class="toolbar">
        <li class="btn-item"><a title="Recargar" href="#" class="fa fa-sync" aria-hidden="true" onclick='window.location.replace("/admin/postulaciones");'><span>Recargar</span></a></li>
    </ol>
@endsection
@section('contenido')
@include('sistema.msg')
<table id="grilla" class="display">
    <thead>
        <tr>
            <th>Nº</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Domicilio</th>
            <th>CV</th>
            <th>Eliminar</th>
        </tr>
    </thead>
</table> 
<script>
    function eliminar(idpostulacion) {
        $.ajax({
            type: "GET",
            url: "{{ route('postulaciones.eliminar') }}",
            data: { id:idpostulacion },
            async: true,
            dataType: "json",
            success: function (data) {
                msgShow(data.msg, data.err);
                $('#grilla').DataTable().ajax.reload();
            }
        });
    }

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
            null,
            { searchable: false, orderable: false },
            { searchable: false, orderable: false }
        ],
        "order": [[ 0, "desc" ]],
	    "ajax": "{{ route('postulaciones.cargarGrilla') }}"
	});
</script>
@endsection