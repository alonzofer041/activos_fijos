@extends('admin3.app')
@section('contenido')
<?php
$operacion='Agregar';
$estilo='info';
$valor="value"
?>


<div class="col-md-12 col-xs-12 col-xs-12">
  <div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
    <div class="clearfix">
      <div class="pull-left">
        <h4 class="text-blue">Alta de activos</h4>        
      </div>
    </div>
    <form id="alta_activo" action="{{action('ActivoController@upload')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-md-12 col-xs-12 col-sm-12">
      <div class="form-group">
        <label class="form-label">Archivo</label>
        <input type="file" class="form-control" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="activo" value="">
      </div>    
        </div>
      </div>
      <button type="submit" class="btn btn-success">Enviar</button>
      
    </form>
  </div>
</div> 
@endsection

@section('documentready')
$(document).ready(function(){
    

  

});
@endsection