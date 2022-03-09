@extends('plantilla.app')
@section('contenido')
 	<div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Alta de Resguardosss</h1>
       <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
 	</div>
		<hr>

	<div class="widget-box">
	  
	  <div class="widget-title"> <span class="icon"> <i class="glyphicon glyphicon-th-list"></i> </span>
	    <input type="button" value="Agregar Resguardo" name="" class="btn btn-info">
	    <input type="button" value="Cancelar" name="" class="btn btn-warning">

	  </div>
	  <br>
	  <div class="fila">
		  	<div class="inputes" style="width:40% ;">
			  	<div><label class="etiqueta">Responsable</label></div>
			  	<input type="text" name="" class="input" placeholder="Buscar...">
			  	
		  	</div>	
		  	<div class="butonb" style="width: 3%;">
			  	<button class="buttond" disabled>
			  		<i class="fas fa-search fa-sm"></i>
			  	</button>
			 </div>
	  </div>

	  <div class="fila">
	  	<div class="inputes" style="width: 25%;">
		  	<div><label class="etiqueta">Numero de Resguardo</label></div>
		  	<input type="text" name="" class="input">
	  	</div>
	  </div>
	  <hr>

	  <table class="tt">
	  	<tr class="tabb">
	  		<td class="division">Nombre: </td>
	  		<td class="infodiv">Jorge Elias Marrufo Muñoz</td>
	  	</tr>
	  	<tr class="tabb">
	  		<td class="division">Puesto: </td>
	  		<td class="infodiv">Maestro</td>
	  	</tr>
	  	<tr class="tabb">
	  		<td class="division">Telefono: </td>
	  		<td class="infodiv">99902459</td>
	  	</tr>
	  	<tr class="tabb">
	  		<td class="division">Correo: </td>
	  		<td class="infodiv">elias.marrufo@utmetropolitana.edu.mx</td>
	  	</tr>
	  	<tr class="tabb">
	  		<td class="division">Matricula: </td>
	  		<td class="infodiv">3853464343</td>
	  	</tr>
	  	<tr class="tabb">
	  		<td class="division">Area: </td>
	  		<td class="infodiv">TIC</td>
	  	</tr>
	  </table>
	</div>
	<br>
	<hr>

	<div class="widget-content nopadding">
	    <table class="table table-bordered titulo">
	      <thead class="color">
	        <tr>
	          
	          <th style="vertical-align:middle;">
	          	<div >idactivo</div>
	          </th>

	          <th style="vertical-align:middle;">
	          	<div>Clave Interna</div>
	          	<div>Activo</div>
	          	<div>Razon Social</div>
	          	<div>Inventariable</div>
	          	<div>Tipo de Bien</div>
	          </th>
	          
	          <th style="vertical-align:middle;">
	          	<div>Numero de Serie</div>
	          	<div>Id Identificacion</div>
	          	<div>Cantidad</div>
	          	<div>Marca</div>
	          	<div>Modela</div>
	          	<div>Precio Unitario</div>
	          	<div>Subtotal</div>
	          	<div>I.V.A</div>
	          	<div>Total</div>
	          </th>

	          <th style="vertical-align:middle;">
	          	<div>Descripcion</div>

	          <th style="vertical-align:middle;"> 
	          	<div>Status</div>
	          	<div>Partida</div>
	          	<div>Estado de Resguardo</div>
	          	<div>Fecha de Registro</div>
	          </th>

	        </tr>

	      </thead>
	      <tbody>
	        <tr>	

	        <td style="vertical-align:middle;">
	          	<div>idactivo</div>
	          </td>

	          <td style="vertical-align:middle;">
	          	<div class="marcado">Clave Interna</div>
	          	<div>Activo</div>
	          	<div>Razon Social</div>
	          	<div>Inventariable</div>
	          	<div>Tipo de Bien</div>
	          </td>
	          
	          <td style="vertical-align:middle;">
	          	<div class="marcado">Numero de Serie</div>
	          	<div>Id Identificacion</div>
	          	<div>Cantidad</div>
	          	<div>Marca</div>
	          	<div>Modela</div>
	          	<div>Precio Unitario</div>
	          	<div>Subtotal</div>
	          	<div>I.V.A</div>
	          	<div>Total</div>
	          </td>

	          <td style="vertical-align:middle;">
	          	<div>Descripcion</div>
	          </td>

	          <td style="vertical-align:middle;">
	          	<div class="marcado">Status</div>
	          	<div>Partida</div>
	          	<div>Estado de Resguardo</div>
	          	<div>Fecha de Registro</div>
	          </td>

	        </tr>

	         <tr>	

	        <td style="vertical-align:middle;">
	          	<div>idactivo</div>
	          </td>

	          <td style="vertical-align:middle;">
	          	<div class="marcado">Clave Interna</div>
	          	<div>Activo</div>
	          	<div>Razon Social</div>
	          	<div>Inventariable</div>
	          	<div>Tipo de Bien</div>
	          </td>
	          
	          <td style="vertical-align:middle;">
	          	<div class="marcado">Numero de Serie</div>
	          	<div>Id Identificacion</div>
	          	<div>Cantidad</div>
	          	<div>Marca</div>
	          	<div>Modela</div>
	          	<div>Precio Unitario</div>
	          	<div>Subtotal</div>
	          	<div>I.V.A</div>
	          	<div>Total</div>
	          </td>

	          <td style="vertical-align:middle;">
	          	<div>Descripcion</div>
	          </td>

	          <td style="vertical-align:middle;">
	          	<div class="marcado">Status</div>
	          	<div>Partida</div>
	          	<div>Estado de Resguardo</div>
	          	<div>Fecha de Registro</div>
	          </td>

	        </tr>
	        

	      </tbody>

	    </table>
	  </div>
@endsection