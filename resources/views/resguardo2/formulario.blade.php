@extends('plantilla.app')

<!-------------------- TARJETAS  --------------------->

@section('establee')

    @import url("http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,400italic");
    @import url("//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css");
    
    .targeer {
    display: inline-block;
		padding: 50px 25px;
		border-left: 1px solid #BDBDBD;
	}

	.corche{
		border-radius: 6px;
		padding: 15px 15px;
		border: 1px solid #BDBDBD;
		background: #F2F2F2;
		box-shadow: 0 0 10px #848484;
	}

	.targeer2 {
    display: inline-block;
		padding: 50px 30px;
	}

	.division1{
		width: 50%; display: inline-block; 
		padding: 0 5px;
		border-right: 1px solid #BDBDBD; 
	}

	.division2{
		width: 50%; display: 
		inline-block;
		padding: 0 5px;
	}

/*------------------ DIVISION TAGET ---------------------*/

    .event-list {
		list-style: none;
		font-family: 'Lato', sans-serif;
		margin: 0px;
		padding: 0px;
	}
	.event-list > li {
		background-color: rgb(255, 255, 255);
		box-shadow: 0px 0px 5px rgb(51, 51, 51);
		box-shadow: 0px 0px 5px rgba(51, 51, 51, 0.7);
		padding: 0px;
		margin: 0px 0px 20px;
	}
	.event-list > li > time {
		display: inline-block;
		width: 100%;
		color: rgb(255, 255, 255);
		background-color: rgb(197, 44, 102);
		padding: 5px;
		text-align: center;
		text-transform: uppercase;
	}
	.event-list > li:nth-child(even) > time {
		background-color: rgb(165, 82, 167);
	}
	.event-list > li > time > span {
		display: none;
	}
	.event-list > li > time > .day {
		display: block;
		font-size: 56pt;
		font-weight: 100;
		line-height: 1;
	}
	.event-list > li time > .month {
		display: block;
		font-size: 10px;
		font-weight: 400;
		line-height: 1;
		margin-top:5px;
	}
	.event-list > li > img {
		width: 100%;
	}
	.event-list > li > .info {
		padding-top: 5px;
		text-align: center;
	}
	.event-list > li > .info > .title {
		font-size: 17pt;
		font-weight: 700;
		margin: 0px;
	}
	.event-list > li > .info > .desc {
		font-size: 13pt;
		font-weight: 300;
		margin: 0px;
	}
	.event-list > li > .info > ul,
	.event-list > li > .social > ul {
		display: table;
		list-style: none;
		margin: 10px 0px 0px;
		padding: 0px;
		width: 100%;
		text-align: center;
	}
	.event-list > li > .social > ul {
		margin: 0px;
	}
	.event-list > li > .info > ul > li,
	.event-list > li > .social > ul > li {
		display: table-cell;
		cursor: pointer;
		color: rgb(30, 30, 30);
		font-size: 11pt;
		font-weight: 300;
        padding: 3px 0px;
	}
    .event-list > li > .info > ul > li > a {
		display: block;
		width: 100%;
		color: rgb(30, 30, 30);
		text-decoration: none;
	} 
    .event-list > li > .social > ul > li {    
        padding: 0px;
    }
    .event-list > li > .social > ul > li > a {
        padding: 3px 0px;
	} 
	.event-list > li > .info > ul > li:hover,
	.event-list > li > .social > ul > li:hover {
		color: rgb(30, 30, 30);
		background-color: rgb(200, 200, 200);
	}
	.facebook a,
	.twitter a,
	.google-plus a {
		display: block;
		width: 100%;
		color: rgb(75, 110, 168) !important;
	}
	.twitter a {
		color: rgb(79, 213, 248) !important;
	}
	.google-plus a {
		color: rgb(221, 75, 57) !important;
	}
	.facebook:hover a {
		color: rgb(255, 255, 255) !important;
		background-color: rgb(75, 110, 168) !important;
	}
	.twitter:hover a {
		color: rgb(255, 255, 255) !important;
		background-color: rgb(79, 213, 248) !important;
	}
	.google-plus:hover a {
		color: rgb(255, 255, 255) !important;
		background-color: rgb(221, 75, 57) !important;
	}

	@media (min-width: 768px) {
		.event-list > li {
			position: relative;
			display: block;
			width: 100%;
			height: 120px;
			padding: 0px;
		}
		.event-list > li > time,
		.event-list > li > img  {
			display: inline-block;
		}
		.event-list > li > time,
		.event-list > li > img {
			width: 120px;
			float: left;
		}
		.event-list > li > .info {
			background-color: rgb(245, 245, 245);
			overflow: hidden;
		}
		.event-list > li > time,
		.event-list > li > img {
			width: 120px;
			height: 120px;
			padding: 0px;
			margin: 0px;
		}
		.event-list > li > .info {
			position: relative;
			height: 120px;
			text-align: left;
			padding-right: 40px;
		}	
		.event-list > li > .info > .title, 
		.event-list > li > .info > .desc {
			padding: 0px 10px;
		}
		.event-list > li > .info > ul {
			position: absolute;
			left: 0px;
			bottom: 0px;
		}
		.event-list > li > .social {
			position: absolute;
			top: 0px;
			right: 0px;
			display: block;
			width: 40px;
		}
        .event-list > li > .social > ul {
            border-left: 1px solid rgb(230, 230, 230);
        }
		.event-list > li > .social > ul > li {			
			display: block;
            padding: 0px;
		}
		.event-list > li > .social > ul > li > a {
			display: block;
			width: 40px;
			padding: 10px 0px 9px;
		}
	}


@endsection
<!-------------------- FIN TARJETAS ------------------>



@section('contenido')


 	<div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Alta de Resguardossss</h1>
       <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
 	</div>
	<hr>
	<div class="row">
		<div class="col-md-8 col-xs-8 col-sm-8">
			<div class="card shadow mb-4">
			  <!-- Card Header - Dropdown -->
			  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
			    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
			    <div class="dropdown no-arrow">
			      <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
			      </a>
			      <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
			        <div class="dropdown-header">Dropdown Header:</div>
			        <a class="dropdown-item" href="#">Action</a>
			        <a class="dropdown-item" href="#">Another action</a>
			        <div class="dropdown-divider"></div>
			        <a class="dropdown-item" href="#">Something else here</a>
			      </div>
			    </div>
			  </div>
			  <!-- Card Body -->
			  <div class="card-body">
			    Aqui va el cuerpo
			  </div>
			</div>
		</div>
	</div>

	<div class="widget-box">
	  
	  <div class="widget-title"> <span class="icon"> <i class="glyphicon glyphicon-th-list"></i> </span>
	    <input type="button" value="Agregar Resguardo" name="" class="btn btn-info" id="guardar">
	    <input type="button" value="Cancelar" name="" class="btn btn-warning">

	  </div>
	  <br>
		<div class="fila">	  
			  <form action="{{action('BuscadorController@activo_resguardo')}}" method="POST">
			{{ csrf_field() }}
			<div class="buscador">
		  	<div><label class="etiqueta">Responsable:</label></div>

                  <div class="input-group">

                    <select style="width: 500px;" name="criterio" id="busqueda-res" class="buscador_usuario"></select>
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit" >
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
              </div>

		</form>
		</div>
	  <div class="fila">
	  	<div class="inputes" style="width: 25%;">
		  	<div><label class="etiqueta">Numero de Resguardo</label></div>
		  	<input type="text" name="" style="width: 470px; padding: 5px;">
	  	</div>
	  </div>
	  
	  <div class="divisor">
	  	<span class="text-divisor">Información del responsable</span>
	  	<hr>
	  </div>

	  <table class="tt"><tr class="tabb">
	  		<td class="division">Nombre: </td>
	  		<td class="infodiv"></td>
	  	</tr>
	  	<tr class="tabb">
	  		<td class="division">Puesto: </td>
	  		<td class="infodiv"></td>
	  	</tr>
	  	<tr class="tabb">
	  		<td class="division">Telefono: </td>
	  		<td class="infodiv"></td>
	  	</tr>
	  	<tr class="tabb">
	  		<td class="division">Email: </td>
	  		<td class="infodiv"></td>
	  	</tr>
	  	<tr class="tabb">
	  		<td class="division">Matricula: </td>
	  		<td class="infodiv"></td>
	  	</tr>
	  	<tr class="tabb">
	  		<td class="division">Area: </td>
	  		<td class="infodiv"></td>
	  	</tr>
	  </table>
	</div>
	<br>
	<hr>

	<div class="widget-content nopadding">
	    <table class="table table-bordered titulo" id="listado">
	      <thead class="color">
	        <tr>

	          <th style="vertical-align:middle;">
	          	<div>Clave Interna</div>
	          	<div>Activo</div>
	          	<div>Razon Social</div>
	          </th>

	          <th style="vertical-align:middle;">
	          	<div>Numero de Serie</div>
	          	<div>Identificacion</div>
	          	<div>Marca</div>
	          	<div>Modelo</div>
	          </th>
	          
	          <th style="vertical-align:middle;">
	          	<div>Cantidad</div>
	          	<div>Precio Unitario</div>
	          	<div>Subtotal</div>
	          	<div>I.V.A</div>
	          	<div>Total</div>
	          </th>

	          <th style="vertical-align:middle;">
	          	<div>Descripcion</div>

	          <th style="vertical-align:middle;">
	          	<div>Partida</div>
	          	<div>Inventariable</div>
	          	<div>Tipo de Bien</div>
	          </th>

	          <th style="vertical-align: middle;">
	          	<button class="mas" id="agregar">
	          		+
	          	</button>
	          </th>

	        </tr>

	      </thead>
	      <tbody>
	        

	      </tbody>

	    </table>
	    <textarea id="dts"></textarea>
	  </div>

	  <div class="row">
	  	<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 targeer2">


	  	<div class="d-sm-flex align-items-center justify-content-between mb-4">
	      <h1 class="h3 mb-0 text-gray-800">Alta de Activo Fijo</h1>
	       <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
 		</div>
		<hr>

	  <div class="widget-box">
	  
	  <div class="divisor">
	  	<span class="text-divisor">Información del activo</span>
	  	<hr>
	  </div>
	  	<form id="alta_activo" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
	  		{{ csrf_field() }}
	  		<div class="fila">
	  			<input type="hidden" name="idactivo" value="" class="form-control">
	  			<div class="inputes" style="width: 49%;">
		  			<div><label class="etiqueta">Nombre del activo</label></div>
		  			<input type="text" name="nombre" class="input" id="nombre" required>
		  		</div>
		  		<div class="inputes" style="width: 49%">
				  	<div><label class="etiqueta">No de serie</label></div>
				  	<input type="text" name="no_serie" id="no_serie" class="input"required> 
		  		</div>
		  		
		  		<div class="inputes" style="width: 34%;">
		  			<div><label class="etiqueta">Marca</label></div>
		  			<input type="text" name="marca" id="marca" class="input" required>
		  		</div>
		  		<div class="inputes" style="width: 33%">
				  	<div><label class="etiqueta">Modelo</label></div>
				  	<input type="text" name="modelo" id="modelo" class="input" required>
		  		</div>
		  		<div class="inputes" style="width: 30%;">
				  	<div><label class="etiqueta">Razon Social</label></div>
				  	<input type="text" name="razon_social" id="razon_social" class="input" required>
			  	</div>
		  	</div>

		  	<div class="fila">
		  		<div class="inputes" style="width: 100%;">
				  	<div><label class="etiqueta">Descripcion</label></div>
				  	<textarea name="descripcion" id="descripcion" class="texter" required></textarea>
			  	</div>
		  	</div>

		  	<div class="divisor">
			  	<span class="text-divisor">Informacion del activo</span>
			  	<hr>
			</div>

			<div class="fila">
			  	<div class="inputes" style="width:49% ;">
				  	<div><label class="etiqueta">Clave Interna</label></div>
				  	<input type="text" id="clave_interna" name="clave_interna" class="input" value="" required>
			  	</div>	
			  	<div class="inputes" style="width: 49%;">
				  	<div><label class="etiqueta">Numero de Identificación</label></div>
				  	<input type="text" id="ididentificacion" name="ididentificacion" class="input" value="" required>
		  		</div>
		    </div>

		    <div class="fila">
		  		<div class="inputes" style="width: 19%;">
				  	<div><label class="etiqueta">Cantidad</label></div>
				  	<input type="text" id="cantidad" name="cantidad" class="input" required>
		  		</div>
			  	<div class="inputes" style="width: 19%;">
				  	<div><label class="etiqueta">Precio Unitario</label></div>
				  	<input type="text" id="precio_u" name="precio_u" class="input" required>
			  	</div>
			  	<div class="inputes" style="width: 19%;">
				  	<div><label class="etiqueta">Subtotal</label></div>
				  	<input type="text" readonly="" id="subtotal" name="subtotal" class="input" required>
		 		 </div>
		 		 <div class="inputes" style="width: 20%;">
				  	<div><label class="etiqueta">I.V.A.</label></div>
				  	<input type="text" id="iva" name="iva" class="input" required>
		  		</div>
		  		<div class="inputes" style="width: 19%;">
				  	<div><label class="etiqueta">Total</label></div>
				  	<input type="text" readonly="" id="total" name="total" class="input" required>
		 		</div>
		  </div>

		 	 <div class="divisor">
			  	<span class="text-divisor">Extra</span>
			  	<hr>
		  	</div>

		  	<div class="fila">
			  <div class="inputes" style="width: 33%;">
			  	<div><label class="etiqueta">Partida</label></div>
			  	<select id="idpartida" name="idpartida" class="input" required>
			  		<option value="" class="etiqueta">Selecciona una partida</option>
        			
			  	</select>
			  </div>
			  <div class="inputes" style="width:32%;">
				  	<div><label class="etiqueta">Tipo de Bien</label></div>
				  	<select id="tipo_activo" name="idtipo_activo" class="input" required>
				  		<option value="">Selecciona Un Tipo</option>
            			
				  	</select>
		  	  </div>
			  <div class="inputes" style="width: 32%;">
			  	<div><label class="etiqueta">Inventariable</label></div>
				  	<select id="inventariable" name="inventariable" class="input" value="" required>
				  		<option value="">Selecciona una opcion</option>
				  		<option  value="Inventariable">Inventariable</option>
				  		<option value="No Inventariable">No inventariable</option>
				  	</select>
			  </div>
		  	</div>

		  	<!-- <div class="inputes" style="width: 19%;">
			  	<input type="hidden" name="idresguardo" class="input" value="0">
	 		</div> -->


	 		<hr class="divisor">

			  <div class="widget-title"> <span class="icon"> <i class="glyphicon glyphicon-th-list"></i> </span>
			  	<br>
			  	<input id="enviar" type="submit" name="operacion" class="btn btn-info" value="Agregar">
			    
			    <input type="button" value="Cancelar" name="" class="btn btn-primary" >

			  </div>
			  <br>

	  	</form>
	</div>

	  	</div>


	  	<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 targeer">
	  		<div class="corche">
	  			<h3 style="display: inline-block;">Targets Agregados</h3>
	  			<h3 style="display: inline-block; float: right; padding: 0px 10px; font-size: 40px;">+</h3>
	  			<hr>
	  			<br>
	  		<ul class="event-list">

					<li>
						<time datetime="2014-07-20 2000">
							<span class="day">1</span>
							<span class="month"><span style="display: block; margin-bottom:2px; ">Inventariable</span> <span style="display: block;">Federal</span></span>
						</time>
						<div class="info">
							<h2 class="title">iMac</h2>
							<div style="margin: 3px 10px;">
							<div style="width: 100%;"><span class="division1" style="background: #E6E6E6;">dasds</span><span class="division2" style="background: #E6E6E6;">dsadas</span></div>
							<div style="width: 100%;"><span class="division1">dasds</span><span class="division2">dsadas</span></div>
							<div style="width: 100%;"><span class="division1" style="background: #E6E6E6;">dasds</span><span class="division2" style="background: #E6E6E6;">dsadas</span></div>
							</div>
						</div>
						<div class="social">
							<ul>
								<li class="facebook" style="width:33%;"><a href="#facebook"><span class="fa fa-edit"></span></a></li>
								<li class="twitter" style="width:34%;"><a href="#twitter"><span class="fa fa-"></span></a></li>
								<li class="google-plus" style="width:33%;"><a href="#google-plus"><span class="fas fa-trash"></span></a></li>
							</ul>
						</div>
					</li>

					<li>
						<time datetime="2014-07-20 2000">
							<span class="day">1</span>
							<span class="month"><span style="display: block; margin-bottom:2px; ">Inventariable</span> <span style="display: block;">Federal</span></span>
						</time>
						<div class="info">
							<h2 class="title">iMac</h2>
							<div style="margin: 3px 10px;">
							<div style="width: 100%;"><span class="division1" style="background: #E6E6E6;">dasds</span><span class="division2" style="background: #E6E6E6;">dsadas</span></div>
							<div style="width: 100%;"><span class="division1">dasds</span><span class="division2">dsadas</span></div>
							<div style="width: 100%;"><span class="division1" style="background: #E6E6E6;">dasds</span><span class="division2" style="background: #E6E6E6;">dsadas</span></div>
							</div>
						</div>
						<div class="social">
							<ul>
								<li class="facebook" style="width:33%;"><a href="#facebook"><span class="fa fa-edit"></span></a></li>
								<li class="twitter" style="width:34%;"><a href="#twitter"><span class="fa fa-"></span></a></li>
								<li class="google-plus" style="width:33%;"><a href="#google-plus"><span class="fas fa-trash"></span></a></li>
							</ul>
						</div>
					</li>

					
				</ul>
			</div>
	  	</div>
	  </div>
	  

	  <script type="text/x-tmpl" id="fila-activo">
	<tr  id="fil_{%=o.indice%}" >
			<td style="width:100px"></td>
			<td>Foto</td>
			<td>{%=o.nombre%}</td>
			<td>{%=o.matr%}</td>
			<td></td>
	</tr>
</script>
@endsection

@section('documentready')
$(document).ready(function(){
	Ui.init({
    dom_tabla:'#listado tbody',
    dom_clave_interna:'#clave_interna',
    dom_nom_activo:'#nom_activo',
    dom_num_serie:'#num_serie',
    dom_identificacion:'#identificacion',
    dom_cantidad:'#cantidad',
    dom_precio_u:'#precio_u',
    dom_descripcion:'#descripcion',
    dom_partida:'#partida',
    dom_inventariable:'#inventariable',
    dom_agregar:'#agregar',
    dom_textarea:'#dts',
    dom_guardar:'#guardar',
    dom_edit:'.edit',
    dom_eliminar:'.eliminar',
    });

    $('#busqueda-res').selectize({
      valueField: 'idusuario',
      labelField: 'nombre',
      searchField: 'nombre',
      placeholder: 'Search...',
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
                url: '{{action('AltaResguardoController@ajax')}}',
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

@section('scripts')
<script src="{{ asset('js/tmpl.min.js') }}"></script> 
@endsection
