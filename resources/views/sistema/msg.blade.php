<div id="msg"></div>
@if(Session::has('msg'))
<script>msgShow('{{ Session::get("msg")["MSG"] }}', '{{ Session::get("msg")["ESTADO"] }}')</script>
@endif