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
      <h1 class="h3 mb-0 text-gray-800">Formulario de Usuario</h1>
       <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
 	</div>
		<hr>

	<div class="widget-box">
	  
	  <!--<div class="divisor">
	  	<span class="text-divisor">Información del usuario</span>
	  	<hr>
	  </div>-->
	<form action="{{action('UsuarioController@guardar')}}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            {{ csrf_field() }}

        <input type="hidden" name="idusuario" class="inputes" value="{{$usuario->idusuario}}"/>

	  	<div class="fila">
	  		<div class="inputes" style="width: 50%;">
			  	<div><label class="etiqueta">Nombre:</label></div>
			  	<input type="text" name="nomusuario" value="{{$usuario->nomusuario}}" class="input">
		  	</div>
		  	<div class="inputes" style="width: 49%;">
				 <div><label class="etiqueta">Apellidos:</label></div>
				 <input type="text" name="apellidos" value="{{$usuario->apellidos}}" class="input">
			</div>	
	  	</div>

	  	<div class="fila">
	  		<div class="inputes" style="width: 34%;">
	  			<div><label class="etiqueta">Puesto:</label></div>
	  			<select class="input" name="idrol">
				 	<option value="0">Selecciona un puesto</option>

				 @foreach($rol as $listado)
	                  <?php
	                  $select='';
	                  if ($listado->idrol==$usuario->idrol) {
	                    $select=' selected ';
	                  }
	                  ?>
                  <option {{$select}} value="{{$listado->idrol}}">{{$listado->nomrol}}</option>
                  @endforeach
				</select>
	  		</div>
	  		<div class="inputes" style="width: 34%">
			  	<div><label class="etiqueta">Telefono:</label></div>
			  	<input type="text" name="telefono" value="{{$usuario->telefono}}" class="input">
	  		</div>
	  		<div class="inputes" style="width: 30%;">
			  	<div><label class="etiqueta">Grado Academico:</label></div>
			  	<input type="text" name="gdr_academico" value="{{$usuario->gdr_academico}}" class="input">
		  	</div>
	  	</div>

	  	<div class="fila">
	  		<div class="inputes" style="width: 50%;">
			  	<div><label class="etiqueta">Corrreo:</label></div>
			  	<input type="text" name="email" value="{{$usuario->email}}" class="input">
		  	</div>
		  	<div class="inputes" style="width: 49%;">
				 <div><label class="etiqueta">Password:</label></div>
				 <input type="password" name="password" value="{{$usuario->password}}" class="input">
			</div>	
	  	</div>

	  	<div class="fila">
	  		<div class="inputes" style="width: 50%;">
			  	<div><label class="etiqueta">Area:</label></div>
			  	<select class="input" name="idarea">
				 	<option value="0">Selecciona un area</option>

				 	@foreach($area as $lista)
	                  <?php
	                  $select='';
	                  if ($lista->idarea==$usuario->idarea) {
	                    $select=' selected ';
	                  }
	                  ?>
                  <option {{$select}} value="{{$lista->idarea}}">{{$lista->nomarea}}</option>
                  @endforeach
				</select>
		  	</div>
		  	<div class="inputes" style="width: 49%;">
				 <div><label class="etiqueta">Matricula:</label></div>
				 <input type="text" name="matricula" value="{{$usuario->matricula}}" class="input">
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