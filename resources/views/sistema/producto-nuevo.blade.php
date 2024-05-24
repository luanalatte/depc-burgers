@extends('plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($producto->idproducto) && $producto->idproducto > 0 ? $producto->idproducto : 0; ?>';
    <?php $globalId = $producto->idproducto ?? "0"; ?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/productos">Productos</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
    <li class="btn-item"><a title="Nuevo" href="/admin/producto/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
    <li class="btn-item"><a title="Guardar" href="#" class="fa fa-save" aria-hidden="true" onclick="javascript: $('#modalGuardar').modal('toggle');"><span>Guardar</span></a>
    </li>
    @if($globalId > 0)
    <li class="btn-item"><a title="Eliminar" href="#" class="fa fa-trash" aria-hidden="true" onclick="javascript: $('#mdlEliminar').modal('toggle');"><span>Eliminar</span></a></li>
    @endif
    <li class="btn-item"><a title="Salir" href="#" class="fa fa-arrow-circle-left" aria-hidden="true" onclick="javascript: $('#modalSalir').modal('toggle');"><span>Salir</span></a></li>
</ol>
<script>
function fsalir(){
    location.href ="/admin/productos";
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
                <input type="text" id="txtNombre" name="txtNombre" class="form-control" value="{{ $producto->nombre }}" required>
            </div>
            <div class="form-group col-lg-6">
                <label>Categoría: *</label>
                <select name="lstCategoria" id="lstCategoria" class="form-control" required>
                    <option value="" selected disabled>Seleccionar</option>
                    @foreach ($aCategorias as $categoria)
                        @if ($categoria->idcategoria == $producto->fk_idcategoria)
                        <option value="{{$categoria->idcategoria}}" selected>{{$categoria->nombre}}</option>
                        @else
                        <option value="{{$categoria->idcategoria}}">{{$categoria->nombre}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-6">
                <label>Cantidad:</label>
                <input type="text" id="txtCantidad" name="txtCantidad" class="form-control" value="{{ $producto->cantidad }}">
            </div>
            <div class="form-group col-lg-6">
                <label>Precio: *</label>
                <input type="number" id="txtPrecio" name="txtPrecio" class="form-control" value="{{ $producto->precio }}" required>
            </div>
            <div class="form-group col-12">
                <label>Descripcion:</label>
                <textarea name="txtDescripcion" id="txtDescripcion" class="form-control" style="min-height: 5rem;">{{ $producto->descripcion }}</textarea>
            </div>
            <div class="form-group col-12">
                <label>Imagen:</label>
                <input type="file" name="fileImagen" id="fileImagen" accept=".jpg, .jpeg, .png, .webp, .gif">
                <br>
                <img src="{{ $producto->imagen }}" alt="imagen">
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
        url: "{{ asset('admin/producto/eliminar') }}",
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