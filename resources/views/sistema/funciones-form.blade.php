<script>
function fsalir(){
    location.href = "{{ $indexUrl }}";
}

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
        url: "{{ asset($formUrl . '/eliminar') }}",
        data: { id:globalId },
        async: true,
        dataType: "json",
        success: function (data) {
            msgShow(data.msg, data.err == 0 ? "success" : "danger");
            $('#mdlEliminar').modal('toggle');
        }
    });
}
</script>