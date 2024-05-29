<div id="msg"></div>
@if(isset($msg))
<script>msgShow('{{ $msg["MSG"] }}', '{{ $msg["ESTADO"] }}')</script>
@endif