@extends('Plantilla.app')
@section('contenido')
 	<div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Listado de Usuarios</h1>
       <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
 	</div>
		<hr>

	<div class="widget-box">
	  
	  <div class="widget-title"> <span class="icon"> <i class="glyphicon glyphicon-th-list"></i> </span>

	  	<form action="{{action('UsuarioController@formulario')}}" method="POST" style="display: inline-block;">
            {{ csrf_field() }}
		  <input type="submit" value="Agregar Usuario" class="btn btn-info">
	    
	    <input type="button" value="Imprimir" name="" class="btn btn-warning">

		</form>
		<form action="{{action('BuscadorController@buscar')}}" method="POST" class="former">
			{{ csrf_field() }}
			<div class="buscador">
                  <div class="input-group">
                    <select style="width: 350px;" name="criterio" id="busqueda_usu" class="buscador_usuario"></select>
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
		          
		          <th style="vertical-align:middle;"><div >idusuario</div></th>
		          <th style="vertical-align:middle;"><div >Nombre</div><div >Apellidos</div></th>
		          <th style="vertical-align:middle;"><div >Puesto</div></th>
		          <th style="vertical-align:middle;"><div >Telefono</div></th>
		          <th style="vertical-align:middle;"><div >Grado Academico</div></th>
		          <th style="vertical-align:middle;"><div >email</div></th>
		          <th style="vertical-align:middle;"><div >Area</div></th>
		          <th style="vertical-align:middle;"><div >Matricula</div> </th>
		          <th style="vertical-align:middle;"><div>Editar</div></th>

		        </tr>

	      </thead>
	      <tbody>

	      	@foreach($usuario as $elemento)
		        <tr>	

			         <td style="vertical-align:middle;"><div >{{$elemento->idusuario}}</div></td>
			         <td style="vertical-align:middle;"><div >{{$elemento->nomusuario}}</div><div >{{$elemento->apellidos}}</div></td>
			         <td style="vertical-align:middle;"><div >{{$elemento->idrol}}</div></td>
			         <td style="vertical-align:middle;"><div >{{$elemento->telefono}}</div></td>
			         <td style="vertical-align:middle;"><div >{{$elemento->gdr_academico}}</div></td>
					 <td style="vertical-align:middle;"><div >{{$elemento->email}}</div></td>
			         <td style="vertical-align:middle;"><div >{{$elemento->idarea}}</div></td>
					 <td style="vertical-align:middle;"><div >{{$elemento->matricula}}</div></td>
			         <td style="vertical-align:middle;"><div><a href="{{action('UsuarioController@formulario_get',$elemento->idusuario) }}"><img src="{{ asset('estilos/img/editar.png') }}" class="editar"></a></div></td>

		        </tr>
	        @endforeach
	      </tbody>

	    </table>
	  </div>
	</div>





@endsection

@section('documentready')
$(document).ready(function(){
    


     $('#busqueda_usu').selectize({
      valueField: 'idusuario',
      labelField: 'nombre',
      searchField: 'nombre',
      create:false, 
      onChange:function(value){         
        // tmp=value.split('_');
        // if(tmp[2]!=0)
        // {
        //  $("#ctrs").html(tmp[2]);
        // }
        // else{
        //   $("#ctrs").html('');   
        // }
        //console.log(value);
      return true;
      },            
      load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: '{{action('UsuarioController@ajax')}}',
                data: {
                q: query,              
                _token:"{{ csrf_token() }}"
                }, 
                type: 'POST',
                error: function(res) {
                    console.log(res.responseText);
                    callback();
                },
                success: function(res) { 
                    console.log(res);
                    callback(res);
                }
            });
        }
  });

  });
@endsection

