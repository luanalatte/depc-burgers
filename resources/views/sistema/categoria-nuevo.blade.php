@extends('plantilla')
@section('titulo', "$titulo")
@section('scripts')
<script>
    globalId = '<?php echo isset($categoria->idcategoria) && $categoria->idcategoria > 0 ? $categoria->idcategoria : 0; ?>';
    <?php
        $globalId = $categoria->idcategoria ?? "0";
        $readonly = !($permisoEditar ?? false) && $globalId > 0;
    ?>
</script>
@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/admin/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/categorias">Categorías</a></li>
    <li class="breadcrumb-item active">Modificar</li>
</ol>
<ol class="toolbar">
    <li class="btn-item"><a title="Nuevo" href="/admin/categoria/nuevo" class="fa fa-plus-circle" aria-hidden="true"><span>Nuevo</span></a></li>
    @if(!$readonly)
        <li class="btn-item"><a title="Guardar" href="#" class="fa fa-save" aria-hidden="true" onclick="javascript: $('#modalGuardar').modal('toggle');"><span>Guardar</span></a></li>
    @endif
    @if($permisoBaja ?? false && $globalId > 0)
        <li class="btn-item"><a title="Eliminar" href="#" class="fa fa-trash" aria-hidden="true" onclick="javascript: $('#mdlEliminar').modal('toggle');"><span>Eliminar</span></a></li>
    @endif
    <li class="btn-item"><a title="Salir" href="#" class="fa fa-arrow-circle-left" aria-hidden="true" onclick="javascript: $('#modalSalir').modal('toggle');"><span>Salir</span></a></li>
</ol>
@endsection
@section('contenido')
@include('sistema.msg')
<div class="panel-body">
    <form id="form1" method="POST">
        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"></input>
            <input type="hidden" id="id" name="id" class="form-control" value="{{$globalId}}" required>
            <div class="form-group col-lg-6">
                <label>Nombre: *</label>
                <input type="text" id="txtNombre" name="txtNombre" class="form-control" value="{{ $categoria->nombre }}" required {{ $readonly ? 'readonly' : '' }}>
            </div>
            <div class="form-group col-lg-6">
                <label>Posición: *</label>
                <input type="number" id="txtPosicion" name="txtPosicion" class="form-control" value="{{ $categoria->posicion }}" {{ $readonly ? 'readonly' : '' }}>
            </div>
        </div>
    </form>
</div>
@include('sistema.funciones-form', ['formUrl'=> 'admin/categoria', 'indexUrl'=> '/admin/categorias'])
@endsection