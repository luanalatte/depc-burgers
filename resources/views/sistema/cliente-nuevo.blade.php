@extends('plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($cliente->idcliente) && $cliente->idcliente > 0 ? $cliente->idcliente : 0; ?>';
    <?php $globalId = $cliente->idcliente ?? "0"; ?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/clientes">Clientes</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
    <li class="btn-item"><a title="Nuevo" href="/admin/cliente/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
    <li class="btn-item"><a title="Guardar" href="#" class="fa fa-save" aria-hidden="true" onclick="javascript: $('#modalGuardar').modal('toggle');"><span>Guardar</span></a>
    </li>
    @if($globalId > 0)
    <li class="btn-item"><a title="Eliminar" href="#" class="fa fa-trash" aria-hidden="true" onclick="javascript: $('#mdlEliminar').modal('toggle');"><span>Eliminar</span></a></li>
    @endif
    <li class="btn-item"><a title="Salir" href="#" class="fa fa-arrow-circle-left" aria-hidden="true" onclick="javascript: $('#modalSalir').modal('toggle');"><span>Salir</span></a></li>
</ol>
<script>
function fsalir(){
    location.href ="/admin/clientes";
}
</script>
@endsection
@section('contenido')
<div id="msg"></div>
@if(isset($msg))
    <script>msgShow('{{ $msg["MSG"] }}', '{{ $msg["ESTADO"] }}')</script>
@endif
<div class="panel-body">
    <form id="form1" method="POST">
        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            <input type="hidden" id="id" name="id" class="form-control" value="{{$globalId}}" required>
            <div class="form-group col-lg-6">
                <label>Nombre: *</label>
                <input type="text" id="txtNombre" name="txtNombre" class="form-control" value="{{ $cliente->nombre }}" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Apellido: *</label>
                <input type="text" id="txtApellido" name="txtApellido" class="form-control" value="{{ $cliente->apellido }}" required>
            </div>
            <div class="form-group col-lg-6">
                <label>DNI: *</label>
                <input type="text" id="txtDNI" name="txtDNI" class="form-control" value="{{ $cliente->dni }}" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Email: *</label>
                <input type="email" id="txtEmail" name="txtEmail" class="form-control" value="{{ $cliente->email }}" required>
            </div>
            @if(!$globalId)
            <div class="form-group col-lg-6">
                <label>Clave: *</label>
                <input type="password" id="txtClave" name="txtClave" class="form-control" value="" required>
            </div>
            @endif
            <div class="form-group col-lg-6">
                <label>Teléfono:</label>
                <input type="tel" id="txtTelefono" name="txtTelefono" class="form-control" value="{{ $cliente->telefono }}">
            </div>
        </div>
    </form>
</div>
<script>

$("#form1").validate();

function guardar() {
    if ($("#form1").valid()) {
        modificado = false;
        form1.submit();
    } else {
        $("#modalGuardar").modal('toggle');
        msgShow("Corrija los errores e intente nuevamente.", "danger");
        return false;
    }
}

function eliminar() {
    $.ajax({
        type: "GET",
        url: "{{ asset('admin/cliente/eliminar') }}",
        data: { id:globalId },
        async: true,
        dataType: "json",
        success: function (data) {
            if (data.err == "0") {
                msgShow("Registro eliminado exitosamente.", "success");
            } else {
                msgShow(data.err, "danger");
            }
            $('#mdlEliminar').modal('toggle');
        }
    });
}

</script>
@endsection