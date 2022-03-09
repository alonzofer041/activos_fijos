@extends('admin3.app')

@section('estilos')
.notification-list ul.adjust li  h3{
	font-size:18px;
    color: #33484f;
    font-weight: 500;
    font-family: 'Work Sans', sans-serif;
}

.notification-list ul.adjust li  h3 span {
    float: right;
    font-size: 14px;
    font-weight: 500;
    padding-top: 2px;
}
@endsection

@section('scripts')
<script src="{{asset('public/js/selectizeb4.js')}}"></script>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('public/css/selectize.bootstrap4.css')}}">
@endsection

@section('contenido')
<?php
?>
<div class="row clearfix">
				<div class="col-xl-6 col-lg-8 col-md-8 col-sm-12 mb-30">
					<div class="bg-white pd-20 box-shadow border-radius-5 xs-pd-20-10 height-100-p">

						<div class="clearfix mb-20">
							<div class="pull-left">
								<h4 class="mb-30">Últimos resguardos registrados</h4>				
							</div>
							<div class="pull-right">
							    <form method="GET" action="{{action('ResguardoController@formulario')}}">
								<button type="submit" class="btn" data-bgcolor="#3b5998" id="add_activo" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);"><i class="fa fa-plus-circle"></i> Agregar Resguardo</button>
								</form>
							</div>
						</div>
						<div class="row mb-20">
							<div class="col-md-12 col-xs-12 col-xs-12">
							    <form action="" method="POST">
								<div class="input-group mb-3">					  
								  <input type="text" id="criterio" name="criterio" class="form-control" placeholder="Personal,#Resguardo" aria-label="Recipient's username" aria-describedby="button-addon2">
								  <div class="input-group-append">
								    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar</button>
								  </div>
								</div>
								</form>				
							</div>
							
					    </div>
						<div class="blog-list">
							<ul>
							    @foreach($resguardos as $resguardo)
							    
							    <li class="pd-20">	
							        								
										<h5 class="clearfix"># {{$resguardo->no_resguardo}} {{$resguardo->nomusuario.' '.$resguardo->apellidos}}</h5>
										<p class="mb-1">
											<span>{{formato_fecha($resguardo->fecha,1)}}</span><br/>
											<a href="#" class="print" data-k="{{$resguardo->idresguardo}}" style="font-size:18px"><i class="icon-copy fa fa-print" aria-hidden="true"></i></a>

											<a href="#" class="edit" data-k="{{$resguardo->idresguardo}}" style="margin-left:10px;font-size:18px"><i class="icon-copy fa fa-pencil" aria-hidden="true"></i></a>
											
										</p>
								</li>
								
							    @endforeach
							</ul>
						</div>
					</div>
				</div>
				<div class="col-xl-6 col-lg-4 col-md-4 col-sm-12 mb-30">
					<div class="bg-white pd-20 box-shadow border-radius-5 height-100-p">
						<div class="clearfix">
							<div class="pull-left">
								<h4 class="mb-30">Activos sin resguardo</h4>				
							</div>
							<div class="pull-right">
							    <form action="{{action('ResguardoController@formulario')}}" method="POST">
							    {{csrf_field()}}
							        <textarea name="dts_activos" id="dts" style="display:none"></textarea>
							    	<button type="submit" disabled="true" class="btn" data-bgcolor="#3b5998" id="asign" data-color="#ffffff" style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);"><i class="fa fa-plus-circle"></i> Asignar Resguardo</button>
							    </form>
								
							</div>
						</div>
						
						<div class="profile-task-list pb-30">
							<ul>
							    @foreach($activos as $activo)
								<li>
									<div class="custom-control custom-checkbox mb-5">
										<input type="checkbox" class="custom-control-input chckactivo" 
										       data-k="{{$activo->idactivo}}" id="task-{{$activo->idactivo}}">
										<label class="custom-control-label" for="task-{{$activo->idactivo}}"></label>
									</div>
									<div class="task-type">{{$activo->clave_interna}}</div>
									{{$activo->nombre}}
									<div class="task-assign">{{$activo->razon_social}}<div class="due-date">due date <span>22 February 2019</span></div></div>
								</li>
								@endforeach								
							</ul>
						</div>
					</div>
				</div>
			</div>
<form action="{{action('ResguardoController@print')}}" target="blank" name="frm_print" method="POST">
{{csrf_field()}}
<input type="hidden" id="cvdfs" name="cvdfs">				
</form>

<form name="filter_resguardo" action="{{action('ResguardoController@filter')}}" method="POST">
{{csrf_field()}}
<input type="hidden" id="idresguardo" name="idrssg" value=""/>
</form>

<form action="{{action('ResguardoController@formulario2')}}" name="frm_edit" method="POST">
{{csrf_field()}}
<input type="hidden" id="ddttt" name="cvdfs">				
</form>

@endsection


@section('documentready')
var activos=[];

function indice_activos(iden){
    indice=-1;
	for(i=0;i<activos.length;i++){
	  if(activos[i]==iden)
	  indice=i;
	}
	return indice;
}

$(document).ready(function(){
	$(".chckactivo").bind('click',function(){
	   iden=$(this).data('k');
	   indice=indice_activos(iden);
	   if($(this).prop('checked')) {
			//si no esta entonces checo que no este en el arreglo
			if(indice==-1)
			 activos.push(iden);
	   } else {
	        //si esta entonces lo elimino del arreglo
		    if(indice!=-1)
		    activos.splice(indice,1);
		}

		if(activos.length!=0)
		$("#asign").prop('disabled',false);
		else
		$("#asign").prop('disabled',true);
	});

	$("#asign").bind('click',function(){
	  $("#dts").val(JSON.stringify(activos));	 
	});

	$(".print").bind('click',function(){
	iden=$(this).data('k');
	$("#cvdfs").val(iden);
	document.frm_print.submit();
	});

	$(".edit").bind('click',function(){
	iden=$(this).data('k');
	$("#ddttt").val(iden);
	document.frm_edit.submit();
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
	            $("#idresguardo").val(this.options[value].idregistro);
	            document.filter_resguardo.submit();
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

});
@endsection