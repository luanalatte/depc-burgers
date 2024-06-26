@if(Session::has('msg'))
<div id="msg" class="alert alert-{{ Session::get('msg')['ESTADO'] }}">{{ Session::get('msg')["MSG"] }}</div>
@endif