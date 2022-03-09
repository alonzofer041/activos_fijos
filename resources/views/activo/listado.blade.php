@extends('admin3.app')

@section('contenido')

@section('scripts')
<script src="{{asset('public/js/jquery-qrcode-0.17.0.min.js')}}"></script>
<script src="{{asset('public/js/ui_listado.js')}}"></script>
<script src="{{asset('public/js/selectizeb4.js')}}"></script>
<script src="{{asset('public/js/shim.min.js')}}"></script>
<script src="{{asset('public/js/xlsx.full.min.js')}}"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('public/css/selectize.bootstrap4.css')}}">
@endsection

<div class="row">
	<div class="col-md-8 col-xs-12 col-xs-12">
		<div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
		    <div class="clearfix mb-20">
				<div class="pull-left">
					<h4 class="text-blue">Panel de Activos</h4>				
				</div>
			</div>
			<div class="row mb-20">
				<div class="col-md-6 col-xs-6 col-xs-6">
				    <form action="" method="POST">
					<div class="input-group mb-3">					  
					  <input type="text" id="criterio" name="criterio" class="form-control" placeholder="Personal,Area,#Resguardo,Numero Inventario" aria-label="Recipient's username" aria-describedby="button-addon2">
					  <div class="input-group-append">
					    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
					  </div>
					</div>
					</form>				
				</div>
				<div class="col-md-6 col-xs-6 col-xs-6">
					<div class="btn-list">
					
						<button type="button" onclick="document.add_activo.submit()" class="btn" data-bgcolor="#3b5998" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);"><i class="fa fa-plus"></i> Agregar</button>
						<button type="button" id="excel" class="btn" data-bgcolor="#00b489" data-color="#ffffff" style="background-color:#00b489"><i class="fa fa-download"></i> Exportar</button>
						<button type="button" id="pload" class="btn" data-bgcolor="#c32361" data-color="#ffffff" style=""><i class="fa fa-file-excel-o"></i> Cargar activos</button>					
					</div>
				
				</div>
			</div>
			<div class="row mb-20">
				<div class="col-md-12 col-xs-12 col-xs-12">
					<div class="btn-list">						
						<!-- <button type="button" id="assign" class="btn" data-bgcolor="#1da1f2" data-color="#ffffff" disabled style="color: rgb(255, 255, 255); background-color: rgb(29, 161, 242);"><i class="fa fa-certificate"></i> Asignar Resguardo</button>	 -->					
						<button type="button" disabled id="print_tag" class="btn" data-bgcolor="#007bb5" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(0, 123, 181);"><i class="fa fa-print"></i> Etiquetas</button>
					</div>
				</div>
			</div>
			<div class="blog-list">
				<ul>
				    @foreach($activos as $elemento)
				    <li  class="fil" 
				      data-numact="{{$elemento->clave_interna}}"
				      data-nombre="{{$elemento->nombre}}" 
				      data-serie="{{$elemento->no_serie}}" 
				      data-marca="{{$elemento->marca}}" 
				      data-modelo="{{$elemento->modelo}}" 
				      data-proveedor="{{$elemento->razon_social}}"
				         id="contenido_{{$elemento->idactivo}}" style="padding:20px">
				        <div id="item_{{$elemento->idactivo}}" data-k="{{$elemento->idactivo}}" class="item-resguardo row">
				        	<div class="col-md-2">
				        		<div class="qr-img" data-k="{{$elemento->clave_interna}}"></div>
				        	</div>
				        	<div  class="col-md-10 ">
				        	<div   id="contenido2_{{$elemento->idactivo}}"  class=" mb-30 list-group-item list-group-item-action flex-column align-items-start">
							<div  class="d-flex w-100 justify-content-between">
								<h5 id="nmbract_{{$elemento->idactivo}}" data-pk="{{$elemento->idactivo}}" data-type="text" data-title="Escribir el nombre del activo" class="mb-1">{{$elemento->clave_interna}} | {{$elemento->nombre}}</h5>
								<a href="#" data-k="{{$elemento->idactivo}}" class="text-muted eliminar"><i class="fa fa-print" aria-hidden="true"></i></a>
							</div>
							<p class="mb-1">
							  <span data-type="text" class="mr-10" data-pk="{{$elemento->idactivo}}" data-title="Escribir la marca" id="marca_{{$elemento->idactivo}}"><strong>Marca:</strong>{{$elemento->marca}}</span> <span data-pk="{{$elemento->idactivo}}" class="mr-10" data-type="text" data-title="Escribir el modelo" id="modelo_{{$elemento->idactivo}}"><strong>Modelo:</strong> {{$elemento->modelo}}</span> <span data-pk="{{$elemento->idactivo}}" class="mr-10" data-type="text" data-title="Escribir el modelo" id="modelo_{{$elemento->idactivo}}"><strong>Proveedor:</strong> {{$elemento->razon_social}}</span>
							</p>
							<p class="mb-1">
							  <span  data-pk="{{$elemento->idactivo}}" class="mr-10" data-type="text" data-title="Escribir el numero de serie" id="serie_{{$elemento->idactivo}}"><strong># Serie:</strong> {{$elemento->no_serie}}</span>
							</p>
							<p class="mb-1">
							  <span data-type="text" class="mr-10" data-pk="{{$elemento->idactivo}}" data-title="Escribir la marca" id="marca_{{$elemento->idactivo}}"><strong>Partida:</strong>{{$elemento->recurso}}</span> <span data-pk="{{$elemento->idactivo}}" class="mr-10" data-type="text" data-title="Escribir el modelo" id="modelo_{{$elemento->idactivo}}"><strong>Tipo activo:</strong> {{$elemento->nomtipoact}}</span>
							</p>
							<p class="mb-1">
							  <span data-type="text" class="mr-10" data-pk="{{$elemento->idactivo}}" data-title="Escribir la marca" id="marca_{{$elemento->idactivo}}"><strong>Personal:</strong>{{$elemento->personal}}</span> <span data-pk="{{$elemento->idactivo}}" class="mr-10" data-type="text" data-title="Escribir el modelo" id="modelo_{{$elemento->idactivo}}"><strong>Área:</strong> {{$elemento->nomarea}}</span> <span data-pk="{{$elemento->idactivo}}" class="mr-10" data-type="text" data-title="Escribir el modelo" id="modelo_{{$elemento->idactivo}}"><strong>No. Resguardo:</strong> {{$elemento->no_resguardo}}</span>
							</p>
							<p class="mb-1">
							  <span data-pk="{{$elemento->idactivo}}" data-type="textarea" data-title="Escribir la descripcion" id="descripcion_{{$elemento->idactivo}}">{{$elemento->descripcion}}</span>
							</p>
						</div>
				        	</div>
				        </div>
						
					</li>
				    @endforeach
				</ul>
			</div>
		</div>
	</div>
</div>
<form name="frm_print" target="blank" action="{{action('ActivoController@imprimir_etiquetas')}}" method="POST">
    {{csrf_field()}}
	<textarea style="display:none" name="dts" id="dts"></textarea>
</form>
<form name="frm_assign" action="{{action('ResguardoController@formulario')}}" method="POST">    
    {{csrf_field()}}
	<textarea style="display:none" name="dts_activos" id="dts2"></textarea>
</form>

<form name="add_activo" action="{{action('ActivoController@formulario')}}" method="POST">
{{csrf_field()}}
</form>

<form name="filter_activo" action="{{action('ActivoController@filter')}}" method="POST">
{{csrf_field()}}
<input type="hidden" id="type" name="type" value=""/>
<input type="hidden" id="register" name="register" value=""/>
</form>

<form name="crg_activos" action="{{action('ActivoController@formulario_upload')}}" method="POST">
	{{csrf_field()}}
</form>

@endsection

@section('documentready')
$(document).ready(function(){

	Ui_listado.init();

	$(".qr-img").each(function(){
	  $(this).qrcode({
	    render:"image",
	    size:250,
	    text:"http://localhost/activos_fijos/"+$(this).data('k'),
	    radius:0.2,
	    mode:3,
	    quiet:2
	  })
	});

	$('#criterio').selectize({
	      valueField: 'idregistro',
	      labelField: 'nombre',
	      searchField: 'nombre',
	      placeholder: 'Busqueda...',
	      maxItems:1,
	      create:false, 
	      onChange:function(value){   
	        if(this.options[value]){
	        	$("#type").val(this.options[value].tipo);
	            $("#register").val(this.options[value].idregistro);
	            document.filter_activo.submit();
	        }      
	      return true;
	      },            
	      load: function(query, callback) {
	            if (!query.length) return callback();
	            $.ajax({
	                url: '{{action('SearchController@search_resguardo')}}',
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


	 $("#excel").bind('click',function(){

	        var filename = "activos.xlsx";
	        var ws_name = "activos";

			var data = [];
			data.push([
			             'Num Activo',
			             'Nombre',
			             'No. Serie',
			             'Marca',
			             'Modelo',
			             'Proveedor',			             
			            ]);
			datas=[
			       "numact" 
			      ,"nombre" 
			      ,"serie" 
			      ,"marca" 
			      ,"modelo" 
			      ,"proveedor" 
			      ]
			$(".fil").each(function(){
			   tmp=[]
			   for(i=0;i<datas.length;i++){
			      tmp.push($(this).data(datas[i]));
			   }
			   data.push(tmp);
			});
			
			 
			if(typeof console !== 'undefined') console.log(new Date());
			var wb = XLSX.utils.book_new(), ws = XLSX.utils.aoa_to_sheet(data);
			 
			/* add worksheet to workbook */
			XLSX.utils.book_append_sheet(wb, ws, ws_name);

			/* write workbook */
			XLSX.writeFile(wb, filename);

	});

	$("#pload").bind('click',function(){
	   document.crg_activos.submit();
	});

});
@endsection