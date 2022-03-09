@extends('admin3.app')

@section('scripts')
<script src="{{ asset('public/js/tmpl.min.js') }}"></script> 
@endsection

@section('contenido')
<div class="row">
	<div class="col-md-12 col-xs-12 col-xs-12">
		<div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
			<div class="clearfix">
				<div class="pull-left">
					<h4 class="text-blue">Carga de activos</h4>				
				</div>
			</div>
			<hr>
			<h6 class="m-0 font-weight-bold text-primary">Archivo</h6>
			<form id="alta_activo" action="{{action('ActivoController@carga_excel')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
			{{ csrf_field() }}
			<div class="row">
		  		<div class="col-md-12 col-xs-12 col-sm-12">
		  		    <div class="form-group">
			  		<label class="etiqueta">Archivo excel</label>
			  		<input type="file" name="activo" class="form-control" value=""/>
			  		</div>
		  		</div>
		  	</div>
		  	<div class="row">
			  	<div class="btn-list">
				  	<input id="enviar" type="submit" name="operacion" class="btn btn-success" value="Enviar">   
					<input type="button" value="Cancelar" name="" class="btn btn-primary" >
			  	</div>		  		
		  	</div>
			</form>
		</div>
	</div>
</div>	
<div class="row">
	<div class="col-md-4 col-xs-4 col-sm-4" style="margin-top: 30px">		
		<div class="pd-20 bg-white border-radius-4 box-shadow">
			<h4 class="mb-20">Procesos de carga</h4>
			<span style="font-size:12px;font-weight:bold;padding:5px;color:#f56767"><i class="fa fa-circle" aria-hidden="true"></i> Duplicados</span> <span style="font-size:12px;font-weight:bold;padding:5px;color:#41ccba"><i class="fa fa-circle" aria-hidden="true"></i> Por consolidar</span> <span style="font-size:12px;font-weight:bold;padding:5px;color:#0099ff"><i class="fa fa-circle" aria-hidden="true"></i> Insertados</span>  
			<div  class="list-group">
			    @foreach($procesos as $proceso)
				<a data-k="{{$proceso->idproceso_carga}}" href="#" class="lnk list-group-item list-group-item-action flex-column align-items-start">
					<div class="d-flex w-100 justify-content-between">
					    <?php
					    $tmp=explode(' ',$proceso->created_at);
					    ?>
						<h5 class="mb-1 color-gray">{{formato_fecha($tmp[0])}} #Registros: {{$proceso->num_registros}}</h5>
						<!-- <small>3 days ago</small> -->
					</div>
					
					<div class="progress">
					    <div class="progress-bar" style="background-color:#f56767;color:#FFFFFF;width:<?php echo ceil(($proceso->duplicados/$proceso->num_registros)*100)?>%" role="progressbar">
					      {{$proceso->duplicados}}
					    </div>
					    <div class="progress-bar" style="background-color:#41ccba;color:#FFFFFF;width:<?php echo ceil(($proceso->por_consolidar/$proceso->num_registros)*100)?>%" role="progressbar">
					      {{$proceso->por_consolidar}}
					    </div>
					    <div class="progress-bar" style="background-color:#0099ff;color:#FFFFFF;width:<?php echo ceil(($proceso->insertados/$proceso->num_registros)*100)?>%" role="progressbar">
					      {{$proceso->insertados}}
					    </div>
					</div>					
					<textarea id="js_{{$proceso->idproceso_carga}}" style="display: none"><?php echo $proceso->json;?></textarea>
				</a>
				@endforeach
			</div>
		</div>					
	</div>
	<div class="col-md-8 col-xs-8 col-sm-8" style="margin-top: 30px">		
		<div class="pd-20 bg-white border-radius-4 box-shadow">
			 <h4 class="mb-20">Detalle del proceso</h4>
			 <div style="border-radius:5px;border:1px solid #999999;background-color: #eff5f7;padding: 15px;margin-bottom: 10px">
			 	<div class="row">
			 		<div class="col-md-6 col-xs-6 col-sm-6">
			 		    <label class="control-label">Buscar por:</label>
			 			<input type="text" class="form-control" id="busq" placeholder="Nombre personal,numero resguardo,numero serie" name="">
			 		</div>
			 		<!-- <div class="col-md-6 col-xs-6 col-sm-6">
			 		    <label class="control-label">Status:</label>
			 			<select id="filtr_st" class="form-control">
			 				<option value="">Todos</option>
			 				<option value="2">Por consolidar</option>
			 				<option value="3">Insertado</option>
			 			</select>
			 		</div> -->
			 	</div>
			 </div>
			 <div  class="list-group" id="pr_detail"></div>
		</div>		
	</div>
</div>  




<script type="text/x-tmpl" id="fila-activo">
    {% for (var i=0; i<o.length; i++) { %}
	<a href="#" {% if (o[i].status==1) { %} style="margin-bottom:10px;border-left:5px solid #0099ff" {% } else { %}  {% if (o[i].status==2) { %} style="margin-bottom:10px;border-left:5px solid #41ccba" {% } else { %} style="margin-bottom:10px;border-left:5px solid #0099ff"  {% } %} {% } %}  class="lt-item list-group-item list-group-item-action flex-column align-items-start"
	data-serie="{%=o[i].no_serie%}"
	data-factura="{%=o[i].factura%}"
	data-usuario="{%=o[i].usuario%}"
	data-resguardo="{%=o[i].no_resguardo%}"
	data-status="{%=o[i].status%}"
	>
	<div class="d-flex w-100 justify-content-between">
		<h5 class="mb-1">{%=i+1%}) {%=o[i].descripcion%}</h5>
		<!-- <small class="text-muted">3 days ago</small> -->
	</div>
	<p class="mb-1">
	<strong>Marca</strong>: {%=o[i].marca%}<br/>
	<strong>Modelo</strong>: {%=o[i].modelo%}<br/>
	<strong>#Serie:</strong>: {%=o[i].no_serie%}<br/>
	<strong>Asignado a:</strong>: {%=o[i].usuario%}. 
	{% if (o[i].idusuario!=0) { %} 
	   <span style="font-size:12px;font-weight:bold;padding:5px;color:#000000;"><i class="fa fa-circle" aria-hidden="true"></i> Consolidado</span> 
	{% } else { %} 	
	<span style="font-size:12px;font-weight:bold;padding:5px;color:#f56767"><i class="fa fa-circle" aria-hidden="true"></i> No Consolidado</span>
	{% } %} 
	<br/>
	<strong>Ubicacion:</strong>: {%=o[i].ubicacion%}
	{% if (o[i].idubicacion!=0) { %} 
	   <span style="font-size:12px;font-weight:bold;padding:5px;color:#000000;"><i class="fa fa-circle" aria-hidden="true"></i> Consolidado</span> 
	{% } else { %} 	
	<span style="font-size:12px;font-weight:bold;padding:5px;color:#f56767"><i class="fa fa-circle" aria-hidden="true"></i> No Consolidado</span>
	{% } %} 
	<br/>
	<strong># Resguardo:</strong>: {%=o[i].no_resguardo%}<br/>
	<strong>Factura:</strong>: {%=o[i].factura%}<br/>
	<strong>Razón social:</strong>: {%=o[i].razon_social%}<br/>
	<strong>Partida:</strong>: {%=o[i].partida%}
	{% if (o[i].idpartida!=0) { %} 
	   <span style="font-size:12px;font-weight:bold;padding:5px;color:#000000;"><i class="fa fa-circle" aria-hidden="true"></i> Consolidado</span> 
	{% } else { %} 	
	<span style="font-size:12px;font-weight:bold;padding:5px;color:#f56767"><i class="fa fa-circle" aria-hidden="true"></i> No Consolidado</span>
	{% } %} 
	<br/>
	<strong>Tipo de activo:</strong>: {%=o[i].tipoactivo%}
	{% if (o[i].idtipoactivo!=0) { %} 
	   <span style="font-size:12px;font-weight:bold;padding:5px;color:#000000;"><i class="fa fa-circle" aria-hidden="true"></i> Consolidado</span> 
	{% } else { %} 	
	<span style="font-size:12px;font-weight:bold;padding:5px;color:#f56767"><i class="fa fa-circle" aria-hidden="true"></i> No Consolidado</span>
	{% } %} 
	<br/>
	<strong>Total:</strong>: {%=o[i].total%}<br/></p>
	<!-- <small class="text-muted">Donec id elit non mi porta.</small> -->
</a>
	{% } %}
</script>

@endsection

@section('documentready')
$(document).ready(function(){
	$(".lnk").bind('click',function(){
	  iden=$(this).data('k');
	  cdn=$("#js_"+iden).val();
	  $("#pr_detail").empty();
	  datos={
	    _token:'{{ csrf_token() }}'
	   ,idproceso:iden
	  };
	  $.ajax({
                url: '{{action('ActivoController@obtener_detalle_proceso')}}',
                data:datos, 
                type: 'POST',
                error: function(res) {  
                    console.log(res.responseText);
                },
                success: function(res) { 
                    $("#pr_detail").html(tmpl('fila-activo',res));
                    //console.log(res);                    
                }
            });
	  
	});

	$("#busq").on('input',function(){
		valor=$(this).val();
		//status=$("#filtr_st").val();		
		$(".lt-item").hide();
		filas=$(".lt-item");
		filas=filas.filter(function(){
			bandera=false;
			serie=$(this).data('serie').toString();
			factura=$(this).data('factura').toString();
			usuario=$(this).data('usuario').toLowerCase();			
			resguardo=$(this).data('resguardo').toString();
			it_st=$(this).data('status');
			if((serie.indexOf(valor)>=0)||(factura.indexOf(valor)>=0)||(resguardo.indexOf(valor)>=0)||(usuario.indexOf(valor.toLowerCase())>=0)){			
				bandera=true;
			}
			/*if(status!=''){
				if(it_st==status)
					bandera=true;
				else
					bandera=false;
			}*/
			return bandera;
		});
		filas.show();
	});

	$("#filtr_st").on('change',function(){
		valor=$("#busq").val();
		status=$(this).val();		
		$(".lt-item").hide();
		filas=$(".lt-item");
		filas=filas.filter(function(){
			bandera=false;
			serie=$(this).data('serie').toString();
			factura=$(this).data('factura').toString();
			usuario=$(this).data('usuario').toLowerCase();			
			resguardo=$(this).data('resguardo').toString();
			it_st=$(this).data('status');

			if(it_st==status)
					bandera=true;
			else
				bandera=false;

			if(valor!=''){
				if((serie.indexOf(valor)>=0)||(factura.indexOf(valor)>=0)||(resguardo.indexOf(valor)>=0)||(usuario.indexOf(valor.toLowerCase())>=0)){			
					bandera=true;
				}
			}			
			
			return bandera;
		});
		filas.show();
	});
})
@endsection