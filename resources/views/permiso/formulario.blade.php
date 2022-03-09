@extends('Plantilla.app')
@section('contenido')

<?php
if ($modo=='editar') {
  $operacion='Modificar'; 
  $estilo='warning';
}
else{
  $operacion='Agregar'; 
  $estilo='info';
}
?>

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Formulario de Permisos</h1>
       <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
  </div>
    <hr>

  <div class="widget-box">
    
    <!--<div class="divisor">
      <span class="text-divisor">Información del rol</span>
      <hr>
    </div>-->
  <form action="{{action('PermisoController@guardar')}}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            {{ csrf_field() }}

        <input type="hidden" name="idpermiso" class="inputes" value="{{$permiso->idpermiso}}"/>

      <div class="fila">
        <div class="inputes" style="width: 50%;">
          <div><label class="etiqueta">Permiso:</label></div>
          <input type="text" name="nompermiso" value="{{$permiso->nompermiso}}" class="input">
        </div>
        <div class="inputes" style="width: 49%;">
         <div><label class="etiqueta">Clave Permiso:</label></div>
         <input type="text" name="cve_permiso" value="{{$permiso->cve_permiso}}" class="input">
      </div>  
      </div>


  <hr class="divisor">

    
    <div class="widget-title"> <span class="icon"> <i class="glyphicon glyphicon-th-list"></i> </span>
      <br>

        <input type="submit" name="operacion" class="btn btn-{{$estilo}}" value="{{$operacion}}">
        @if($modo=='editar')
        <input type="submit" name="operacion" class="btn btn-danger" value="Eliminar">
        @endif
        <input type="submit" name="operacion" class="btn btn-primary" value="Cancelar">

    </div>
  </form>
    <br>
  </div>
@endsection