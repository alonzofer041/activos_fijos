@extends('Plantilla.app')
@section('contenido')
 	<div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Listado de Permisos</h1>
       <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
 	</div>
		<hr>

	<div class="widget-box">
	  
	  <div class="widget-title"> <span class="icon"> <i class="glyphicon glyphicon-th-list"></i> </span>

	  	<form action="{{action('PermisoController@formulario')}}" method="POST" style="display: inline-block;">
            {{ csrf_field() }}
		<input type="submit" value="Agregar Usuario" class="btn btn-info">
	    
	    <input type="button" value="Imprimir" name="" class="btn btn-warning">

		</form>
		<form action="" method="POST" class="former">
			{{ csrf_field() }}
			<div class="buscador">
                  <div class="input-group">
                    <select style="width: 350px;" name="criterio" id="busqueda_usu" class="buscador_usuario">
    
                    	<option>1</option>
					</select>
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit" >
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
              </div>

		</form>
	   		 
	  </div>

	  <br>
	  <div class="widget-content nopadding">
	    <table class="table table-bordered titulo">
	      <thead class="color">
		        <tr>
		          
		          <th style="vertical-align:middle;"><div >idpermiso</div></th>
		          <th style="vertical-align:middle;"><div >Permsio</div></th>
		          <th style="vertical-align:middle;"><div >Clave Permiso</div></th>
		          <th style="vertical-align:middle;"><div>Editar</div></th>

		        </tr>

	      </thead>
	      <tbody>

	      	@foreach($permiso as $elemento)
		        <tr>	

			         <td style="vertical-align:middle;"><div >{{$elemento->idpermiso}}</div></td>
			         <td style="vertical-align:middle;"><div >{{$elemento->nompermiso}}</div></td>
			         <td style="vertical-align:middle;"><div >{{$elemento->cve_permiso}}</div></td>
			         <td style="vertical-align:middle;"><div><a href="{{action('PermisoController@formulario_get',$elemento->idpermiso) }}"><img src="{{ asset('estilos/img/editar.png') }}" class="editar"></a></div></td>

		        </tr>
	        @endforeach
	      </tbody>

	    </table>
	  </div>
	</div>
@endsection