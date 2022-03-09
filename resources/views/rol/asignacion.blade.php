@extends('plantilla.app')


@section('contenido')


<h3>Asignar Permisos</h3>
<hr>
<form action="{{action('RolController@asignar_permisos')}}" method="POST">
            {{ csrf_field() }}
<input type="hidden" name="idrol" value="{{$rol->idrol}}">
<button type="submit" class="btn btn-success">Agregar</button>
	<div class="widget-box">
	  <div class="widget-title"> <span class="icon"> <i class="glyphicon glyphicon-th-list"></i> </span>
	  </div>

	  <br>
	  <div class="widget-content nopadding">
	    <table class="table table-bordered table-striped">
	      <thead>
	        <tr>
	          <th>Check</th>
	          <th>Nombre</th>
	        </tr>
	      </thead>
	      <tbody>
	      	@foreach($permiso as $elemento)
	        <tr class="odd gradeX">
	          <td>
	          	<?php
	          	$check='';
	          	if ($rol->permiso->contains($elemento->idpermiso)) {
	          		$check=' checked ';
	          	}
	          	?>
	          	<input type="checkbox" {{$check}} name="idpermiso[]" value="{{$elemento->idpermiso}}">
	          </td>
	          <td>{{$elemento->nompermiso}}</td>       
	        </tr>
	        @endforeach
	      </tbody>
	    </table>
	  </div>
	</div>

</form>
@endsection