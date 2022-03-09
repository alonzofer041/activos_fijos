@extends('admin3.app')
@section('contenido')
<?php
if($modo=='editar'){
	$operacion='Modificar';
	$estilo='warning';
	$identificacion1='identificacion';
}
else{
	$operacion='Agregar';
	$estilo='info';
	$identificacion1='';	
}
$valor="value"
?>

<div class="row">
	<div class="col-md-12 col-xs-12 col-xs-12">
		<div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
			<div class="clearfix">
				<div class="pull-left">
					<h4 class="text-blue">Alta de activos</h4>				
				</div>
			</div>
			<hr>
			<h6 class="m-0 font-weight-bold text-primary">Información general</h6>
			<form id="alta_activo" action="{{action('ActivoController@guardar')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
			{{ csrf_field() }}
			<input type="hidden" name="idactivo" value="{{$activo->idactivo}}" class="form-control">
			<input type="hidden" name="idresguardo" class="input" value="N/A">
			<input type="hidden" name="idubicacion" class="input" value="N/A">
			<div class="row">
		  		<div class="col-md-12 col-xs-12 col-sm-12">
		  		    <div class="form-group">
			  		<label class="etiqueta">Nombre del activo</label>
			  		<input type="text" name="nombre" class="form-control" id="nombre" <?php if($operacion=='Modificar'){echo 'value="'.$activo->identificacion->nombre.'"';}else{echo 'value="'.$activo->nombre.'"';} ?> required>
			  		</div>
		  		</div>
		  		
		  		<div class="col-md-4 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Marca</label>
			  		<input type="text" name="marca" id="marca" class="form-control" <?php if($operacion=='Modificar'){echo 'value="'.$activo->identificacion->marca.'"';}else{echo 'value="'.$activo->marca.'"';} ?> required>
			  		</div>
		  		</div>
		  		<div class="col-md-4 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Modelo</label>
			  		<input type="text" name="modelo" id="modelo" class="form-control" <?php if($operacion=='Modificar'){echo 'value="'.$activo->identificacion->modelo.'"';}else{echo 'value="'.$activo->modelo.'"';} ?> required>
			  		</div>
		  		</div>
		  		<div class="col-md-4 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Razón social</label>
			  		<input type="text" name="razon_social" id="razon_social" class="form-control" value="{{$activo->razon_social}}" required>
			  		</div>
		  		</div>
		  		<div class="col-md-12 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Descripción</label>
			  		<textarea name="descripcion" id="descripcion" class="form-control" required><?php if($operacion=='Modificar'){echo $activo->identificacion->nombre;}else{echo $activo->nombre;} ?></textarea>
			  		</div>
		  		</div>
		  	</div>
		  	<h6 class="m-0 font-weight-bold text-primary">Información del activo</h6>
		  	<div class="row">
		  		<div class="col-md-4 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">No. de serie</label>
			  		<input type="text" name="no_serie" id="no_serie" class="form-control" <?php if($operacion=='Modificar'){echo 'value="'.$activo->identificacion->no_serie.'"';}else{echo 'value="'.$activo->no_serie.'"';} ?> required> 
			  		</div>
		  		</div>
		  		<div class="col-md-4 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Clave interna</label>
			  		<input type="text" id="clave_interna" name="clave_interna" class="form-control" value="{{$activo->clave_interna}}" required>
			  		</div>
		  		</div>
		  		<div class="col-md-4 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Número de identificación</label>
			  		<input type="text" id="ididentificacion" name="ididentificacion" class="form-control" value="{{$activo->ididentificacion}}" required>
			  		</div>
		  		</div>
		  		<div class="col-md-2 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Cantidad</label>
			  		<input type="text" id="cantidad" name="cantidad" class="form-control" <?php if($operacion=='Modificar'){echo 'value="'.$activo->identificacion->cantidad.'"';}else{echo 'value="'.$activo->cantidad.'"';} ?> required>
			  		</div>
		  		</div>
		  		<div class="col-md-2 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Precio unitario</label>
			  		<input type="text" id="precio_u" name="precio_u" class="form-control" <?php if($operacion=='Modificar'){echo 'value="'.$activo->identificacion->precio_u.'"';}else{echo 'value="'.$activo->precio_u.'"';} ?> required>
			  		</div>
		  		</div>
		  		<div class="col-md-2 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Subtotal</label>
			  		<input type="text" readonly="" id="subtotal" name="subtotal" class="form-control" <?php if($operacion=='Modificar'){echo 'value="'.$activo->identificacion->subtotal.'"';}else{echo 'value="'.$activo->subtotal.'"';} ?> required>
			  		</div>
		  		</div>
		  		<div class="col-md-2 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">I.V.A.</label>
			  		<input type="text" id="iva" name="iva" class="form-control" <?php if($operacion=='Modificar'){echo 'value="'.$activo->identificacion->iva.'"';}else{echo 'value="'.$activo->iva.'"';} ?> required>
			  		</div>
		  		</div>
		  		<div class="col-md-4 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Total</label>
			  		<input type="text" readonly="" id="total" name="total" class="form-control" <?php if($operacion=='Modificar'){echo 'value="'.$activo->identificacion->total.'"';}else{echo 'value="'.$activo->total.'"';} ?> required>
			  		</div>
		  		</div>
		  	</div>
		  	<hr/>
		  	<h6 class="m-0 font-weight-bold text-primary">Extra</h6>
		  	<div class="row">
		  		<div class="col-md-4 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Partida</label>
			  		<select id="idpartida" name="idpartida" class="form-control" required>
				  		<option value="" class="etiqueta">Selecciona una partida</option>
	        			@foreach($partidas as $partida)
	        				<?php
	        					$select='';
	        					if ($partida->idpartida==$activo->idpartida) 
	        						$select='selected';
	        				?>
	        				<option {{$select}} value="{{$partida->idpartida}}">{{$partida->recurso}}</option>
	        			@endforeach
				  	</select>
			  		</div>
		  		</div>
		  		<div class="col-md-4 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Tipo de bien</label>
			  		<select id="tipo_activo" name="idtipo_activo" class="form-control" required>
				  		<option value="">Selecciona un tipo</option>
            			@foreach($tipo_activos as $tipo_activo)
            				<?php
            					$select='';
            					if ($tipo_activo->idtipo_activo==$activo->idtipo_activo) 
            						$select='selected';
            				?>
            				<option {{$select}} value="{{$tipo_activo->idtipo_activo}}">{{$tipo_activo->nomtipoact}}</option>
            			@endforeach
				  	</select>
			  		</div>
		  		</div>
		  		<div class="col-md-4 col-xs-12 col-sm-12">
			  		<div class="form-group">
			  		<label class="etiqueta">Inventariable</label>
			  		<select id="inventariable" name="inventariable" class="form-control" value="" required>
				  		<option value="">Selecciona una opción</option>
				  		<?php
            					$select='';
            					if ($activo->inventariable=='Inventariable') {
            						$select='selected';
            						$select2='';
            					}else{
            						$select2='selected';
            					}
            				?>
				  		<option {{$select}} value="Inventariable">Inventariable</option>
				  		<option {{$select2}} value="No Inventariable">No inventariable</option>
				  	</select>
			  		</div>
		  		</div>
		  	</div>
		  	<div class="row">
			  	<div class="btn-list">
				  	<input id="enviar" type="submit" name="operacion" class="btn btn-{{$estilo}}" value="{{$operacion}}">
		          	@if($modo=='editar')
		          		<input type="submit" name="operacion" class="btn btn-danger" value="Eliminar">
		          	@endif			    
					<input type="button" value="Cancelar" name="" class="btn btn-primary" >
			  	</div>		  		
		  	</div>
			</form>
		</div>
	</div>
</div>	  
@endsection

@section('documentready')
function validarFormulario(){
			$(".input").bind('change', function(){
				//iden=$(this).attr('id');
				if ($(this).hasClass("input is-invalid")) {
					console.log("Formulario invalido");
					//$("#cantidad").removeClass("input-valido");
					$(this).addClass("input-invalido");
					$("#enviar").prop('disabled',true);
				}
				else{
					console.log("Formulario valido");
					$(this).removeClass("input-invalido");
					//$("#cantidad").addClass("input-valido");
					$("#enviar").prop('disabled',false);
				}

			});
		}

function calcular_subtotal(){
			cant=$("#cantidad").val();
			prec=$("#precio_u").val();
			subt=cant*prec;
			convertToDecimal=subt.toFixed(2);
			$("#subtotal").val(convertToDecimal);
			//console.log(subt);	
		}
		function total(){
			iva=$("#iva").val();
			subt=$("#subtotal").val();
			tot=subt*(1+'.'+iva);
			convertToDecimal=tot.toFixed(2);
			$("#total").val(convertToDecimal);
			//console.log(convertToDecimal);	
		}
		$(document).ready(function(){
			$("#precio_u").bind('change', function(){
				calcular_subtotal();
				total();
			});
			$("#cantidad").bind('change', function(){
				calcular_subtotal();
				total();
			});
			$("#iva").bind('change', function(){
				total();
			});
		});

		bootstrapValidate(['#nombre','#marca','#modelo','#razon_social','#descripcion','#clave_interna'], 'min:1:Un carácter mínimo permitido!|max:255:255 carateres máximos permitidos!');
		bootstrapValidate(['#no_serie','#ididentificacion','#cantidad','#precio_u','#subtotal','#iva','total'], 'numeric:Introduce solo carateres numéricos|min:1:Un carácter mínimo permitido!');
$(document).ready(function(){
			validarFormulario();
			$(".btn").bind('click', function(){
			});
		});
@endsection