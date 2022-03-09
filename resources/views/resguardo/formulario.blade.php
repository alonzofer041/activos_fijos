@extends('admin3.app')


@section('css')
<link rel="stylesheet" href="{{asset('public/js/bootstrap4-editable/css/bootstrap-editable.css')}}">
<link rel="stylesheet" href="{{asset('public/css/selectize.bootstrap4.css')}}">
@endsection

@section('contenido')

<?php
if(!isset($operacion))
	$operacion='Agregar';
?>

<div class="row">
	<div class="col-md-4 col-xs-12 col-xs-12">
		<div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
			<div class="clearfix">
				<div class="pull-left">
					<h4 class="text-blue">Datos del Resguardo</h4>					
				</div>
			</div>
			<hr>
			<h6 class="m-0 font-weight-bold text-primary">Información general</h6>
			<form name="frm" action="{{action('ResguardoController@save')}}" method="POST">
			  {{csrf_field()}}
			  @isset($resguardo)
			  <input type="hidden" name="idresguardo" value="{{$resguardo->idresguardo}}">
			  @endisset
			  <?php
			  $num_resguardo='';
			  $nomresponsable='';
			  $idresponsable=-1;
			  if(isset($resguardo)){
			  	$num_resguardo=$resguardo->no_resguardo;
			  	$nomresponsable=$resguardo->nomusuario;
			    $idresponsable=$resguardo->idresponsable;

			  }
			  ?>
			   <textarea style="display:none" name="dts" id="dts"></textarea>
			    <input type="hidden" name="idresponsable" value="{{$idresponsable}}" id="idresponsable">
			    <input type="hidden" name="operacion" value="{{$operacion}}" id="operacion">
			    <div class="row">
			        <div class="col-md-12 col-xs-12 col-sm-12">
			    		<div class="form-group">
							<label>No. Resguardo</label>
							<input class="form-control" name="num_resguardo" type="text" placeholder="Nombre,email" value="{{$num_resguardo}}">
						</div>
			    	</div>
			    	<div class="col-md-12 col-xs-12 col-sm-12">
			    		<div class="form-group">
							<label>Responsable</label>
							<input class="form-control" type="text" id="busqueda-res" placeholder="Nombre,email">
						</div>
			    	</div>			    	
			    </div>
			    <div id="personal-sect" style="background-color: #eff5f7;display: none" class="pd-30">
			    <h6 class="m-0 font-weight-bold text-primary">Agregar Personal</h6>
			    <div class="row">			        
			    	<div class="col-md-12 col-xs-12 col-sm-12">
			    		<div class="form-group">
							<label>Nombre</label>
							<input class="form-control" type="text" name="new-nombre" id="new-nombre" placeholder="Nombre,email">
						</div>
			    	</div>
			    	<div class="col-md-12 col-xs-12 col-sm-12">
			    		<div class="form-group">
							<label>Apellido</label>
							<input class="form-control" type="text" name="new-apellido" id="new-apellido" placeholder="Nombre,email">
						</div>
			    	</div>
			    	<div class="col-md-12 col-xs-12 col-sm-12">
			    		<div class="form-group">
							<label>Email</label>
							<input class="form-control" type="text" name="new-email" id="new-email" placeholder="Nombre,email">
						</div>
			    	</div>
			    	<div class="col-md-12 col-xs-12 col-sm-12">
			    		<div class="form-group">
							<label>Telefono</label>
							<input class="form-control" type="text" name="new-telefono" id="new-telefono" placeholder="Nombre,email">
						</div>
			    	</div>
			    	<div class="col-md-12 col-xs-12 col-sm-12">
			    		<div class="form-group">
							<label>Area</label>
							<select name="new-area" class="form-control">
								@foreach($areas as $area)
								<option value="{{$area->idarea}}">{{$area->nomarea}}</option>
								@endforeach
							</select>							
						</div>
			    	</div>
			    </div>			    	
			    </div>
		    	<div class="btn-list">
					<button type="button" id="guardar" class="btn btn-primary">Guardar</button>					
					<button type="button" class="btn btn-danger">Eliminar</button>					
				</div>  	
		    </form>
		</div>
	</div>
	<div class="col-md-8 col-xs-12 col-xs-12">
		<div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
			<div class="clearfix">
					<div class="pull-left">
						<h4 class="text-blue">Activos</h4>					
					</div>
					<div class="pull-right">
						<button type="button" class="btn" data-bgcolor="#3b5998" id="add_activo" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);"><i class="fa fa-plus-circle"></i> Agregar Activo</button>
					</div>
			</div>
			<hr>
			<div class="list-group" id="lista-activos"></div>
		</div>		
	</div>
</div>

<script type="text/x-tmpl" id="fila-activo">
	<div class="mb-30 list-group-item list-group-item-action flex-column align-items-start">
		<div class="d-flex w-100 justify-content-between">
			<h5 id="nmbract_{%=o.iden%}" data-pk="{%=o.iden%}" data-type="text" data-title="Escribir el nombre del activo" class="mb-1">{%=o.nombre%}</h5>
			<a href="#" data-k="{%=o.iden%}" class="text-muted eliminar"><i class="fa fa-trash" aria-hidden="true"></i></a>
		</div>
		<p class="mb-1">
		  <span data-type="text" class="mr-10" data-pk="{%=o.iden%}" data-title="Escribir la marca" id="marca_{%=o.iden%}">{%=o.marca%}</span> <span data-pk="{%=o.iden%}" class="mr-10" data-type="text" data-title="Escribir el modelo" id="modelo_{%=o.iden%}">{%=o.modelo%}</span><br/>
		  Razon Social Proveedor 
		  <select id="razon_{%=o.iden%}" data-pk="{%=o.iden%}">
		  	<option value="{%=o.razon%}">{%=o.razon%}</option>
		  </select>		  
		</p>
		<p class="mb-1">
		  <span data-pk="{%=o.iden%}" data-type="textarea" data-title="Escribir la descripcion" id="descripcion_{%=o.iden%}">{%=o.descripcion%}</span>
		</p>
		<p class="mb-1">
		  <span  data-pk="{%=o.iden%}" class="mr-10" data-type="text" data-title="Escribir el numero de serie" id="serie_{%=o.iden%}">{%=o.serie%}</span> <span data-pk="{%=o.iden%}" class="mr-10" data-type="text" data-title="Escribir la clave" id="clave_{%=o.iden%}">{%=o.clave%}</span>  <span data-pk="{%=o.iden%}" data-type="text" data-title="Escribir la identificacion" id="identificacion_{%=o.iden%}">{%=o.identificacion%}</span>
		</p>
		<table class="table table-bordered">
		   <tr>
		   	<td>Cantidad</td>
		   	<td>Precio</td>
		   	<td>Subtotal</td>
		   	<td>Iva (16%)</td>
		   	<td>Total</td>
		   </tr>
			<tr>
				<td><span data-pk="{%=o.iden%}" data-type="text" id="cantidad_{%=o.iden%}">{%=o.cantidad%}</span></td>
				<td><span data-pk="{%=o.iden%}" data-type="text" id="precio_{%=o.iden%}">{%=o.precio%}</span></td>
				<td><span id="subt_{%=o.iden%}">{%=o.subtotal%}</span></td>
				<td><span id="iva_{%=o.iden%}"></span></td>
				<td><span id="total_{%=o.iden%}">{%=o.total%}</span></td>							
			</tr>
		</table>
		<p class="mb-1">
		  <span data-pk="{%=o.iden%}" class="mr-10" data-type="select" id="partida_{%=o.iden%}" data-value="{%=o.partida%}">{%=o.nompartida%}</span> <span class="mr-10" data-type="select" data-pk="{%=o.iden%}" data-value="{%=o.tipoactivo%}" id="tipobien_{%=o.iden%}" data-value="{%=o.tipoactivo%}">{%=o.nomtipoactivo%}</span> <span data-pk="{%=o.iden%}" data-value="{%=o.inventariable%}" data-type="select" id="inventariable_{%=o.iden%}">Inventariable</span>  
		</p>
		<p class="mb-1">
			<span data-pk="{%=o.iden%}" data-value="{%=o.idubicacion%}" data-type="select" id="ubicacion_{%=o.iden%}">Ubicacion</span>  
		</p>
	</div>
</script>
@endsection

@section('documentready')
$(document).ready(function(){
	Ui.init({
    dom_tabla:'#lista-activos',
    dom_plantilla:'fila-activo',
    dom_botom:'#add_activo',
    dom_eliminar:'.eliminar',

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
    activos:[<?php
    if(isset($activos)){
    	$coma='';
    	foreach($activos as $activo){
    		echo $coma.'{';
    		echo "nombre:'".$activo->nombre."'";
			echo ",marca:'".$activo->marca."'";
			echo ",modelo:'".$activo->modelo."'";
			echo ",razon:'".$activo->razon_social."'";
			echo ',descripcion:"'.$activo->descripcion.'"';
			echo ",serie:'".$activo->no_serie."'";
			echo ",clave:'".$activo->clave_interna."'";					
			echo ",identificacion:'".$activo->no_iden."'";
			echo ",cantidad:".$activo->cantidad."";
			echo ",precio:".$activo->precio_u."";
			echo ",inventariable:'".$activo->inventariable."'";
			echo ",partida:".$activo->idpartida;
			echo ",nompartida:'".$activo->recurso."'";
			echo ",tipoactivo:".$activo->idtipo_activo;
			echo ",nomtipoactivo:'".$activo->nomtipoact."'";
			if(isset($activo->idubicacion))
				echo ",idubicacion:'".$activo->idubicacion."'";
			else
				echo ",idubicacion:'0'";
			
			echo ",id:".$activo->idactivo;
    		echo '}';
    		$coma=',';

    	}
    }
    ?>],
    ubicaciones:[<?php
    if(isset($ubicaciones)){
    	$coma='';
    	foreach($ubicaciones as $ubicacion){
    		echo $coma.'{';    		
			echo "text:'".$ubicacion->clave."'";			
			echo ",value:".$ubicacion->idubicacion;
    		echo '}';
    		$coma=',';

    	}
    }
    ?>],
    token:'{{ csrf_token() }}',
    url_busq_proveedor:'{{action('ActivoController@search_proveedor')}}',
    partidas:[
             <?php
             $coma='';
             foreach($partidas as $partida){
             	echo $coma.'{';
             	echo 'value:'.$partida->idpartida;
             	echo ",text:'".$partida->recurso,"'";
             	echo '}';
             	$coma=',';
             }
             ?>
             ],
    tipos:[
             <?php
             $coma='';
             foreach($tipos as $tipo){
             	echo $coma.'{';
             	echo 'value:'.$tipo->idtipo_activo;
             	echo ",text:'".$tipo->nomtipoact,"'";
             	echo '}';
             	$coma=',';
             }
             ?>
             ],
    
    
    });
    
    $('#busqueda-res').selectize({
      valueField: 'idusuario',
      labelField: 'nombre',
      searchField: 'nombre',
      placeholder: 'Busqueda...',
      maxItems:1,
      create:true, 
      onChange:function(value){      
        if(value.indexOf("@@@")!=-1){
          value=value.replace(/@@@/g, '');
          $("#idresponsable").val(value);          
          $("#personal-sect").hide();
        }
        else{
          $("#idresponsable").val(-1); 
          $("#personal-sect").show();        
        }
        
      return true;
      },            
      load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: '{{action('FuncionarioController@search')}}',
                data: {
                q: query, 
                filter:'@@@',             
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

  @isset($resguardo)
  var $select = $("#busqueda-res").selectize();
  var selectize = $select[0].selectize;  
  selectize.addOption({nombre: "{{$nomresponsable}}", idusuario: "{{$idresponsable}}"});
  selectize.setValue("{{$idresponsable}}",true); 
  @endisset
  

});
@endsection

@section('scripts')
<script src="{{ asset('public/js/tmpl.min.js') }}"></script>
<script src="{{ asset('public/js/objeto.js') }}"></script>
<script src="{{ asset('public/js/bootstrap4-editable/js/bootstrap-editable.min.js') }}"></script>
<script src="{{ asset('public/js/selectizeb4.js') }}"></script>
@endsection
