@extends('Plantilla.app')
@section('contenido')
 	<div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Areas</h1>
       <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
 	</div>
		<hr>

	<div class="widget-box">
	  
	  <div class="widget-title"> <span class="icon"> <i class="glyphicon glyphicon-th-list"></i> </span>

	  	<form action="{{action('AreaController@formulario')}}" method="POST">
            {{ csrf_field() }}
		<input type="submit" value="Agregar Area" class="btn btn-info">
	    
	    <input type="button" value="Imprimir" name="" class="btn btn-warning">

	   		 <div class="buscador">
                <form class="ancc mr-auto navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-1 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button" disabled>
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
		</form>
	  </div>
	  <br>
	  <div class="widget-content nopadding">
	    <table class="table table-bordered titulo">
	      <thead class="color">
	        <tr>
	          <th style="vertical-align: middle;">#</th>
	          <th style="vertical-align: middle;">Area</th>
	          <th style="vertical-align: middle;">Cve Area</th>
	          <th style="vertical-align: middle;">Editar</th>
	        </tr>
	      </thead>
	      <tbody>
	      	@foreach($area as $elemento)
	        <tr class="odd gradeX">
	          <td style="vertical-align: middle;">{{$elemento->idarea}}</td>
	          <td style="vertical-align: middle;">{{$elemento->nomarea}}</td>
	          <td style="vertical-align: middle;">{{$elemento->cve_area}}</td>     
	          <td style="vertical-align: middle;"><a href="{{ action('AreaController@formulario_get',$elemento->idarea) }}"><img src="{{ asset('estilos/img/editar.png') }}" class="editar"></a></td>	       
	        </tr>
	        @endforeach
	      </tbody>
	    </table>
	  </div>
	</div>
@endsection