@extends('admin3.app')

@section('estilos')
.ubicado{
	background-color:#CCCCCC !important;
}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('public/css/selectize.bootstrap4.css')}}">
@endsection

@section('contenido')
<div class="row">
	<div class="col-md-12 col-xs-12 col-xs-12">
		<div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
			<div class="clearfix">
				<div class="pull-left">
					<h4 class="text-blue">Índice inverso</h4>		
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-xs-12 col-xs-12">
				<form name="frm" action="{{action('ActivoController@indice_inverso')}}" method="POST">
				{{csrf_field()}}
				<select name="entidad" onchange="document.frm.submit()" class="form-control">
					<option @if($opcion=='') selected @endif value="">Seleccione una opcion</option>
					<option @if($opcion=='usuario') selected @endif value="usuario">Usuario</option>
					<option @if($opcion=='ubicacion') selected @endif value="ubicacion">Ubicacion</option>
					<option @if($opcion=='partida') selected @endif value="partida">Partida</option>
					<option @if($opcion=='tipo_activo') selected @endif value="tipo_activo">Tipo Activo</option>
				</select>
				</form>
				</div>
			</div>
			<div style="margin-top: 20px" class="row">
				<div class="col-md-12 col-xs-12 col-xs-12">
				<table class="table">
				    @foreach($indices as $indice)
					<tr @if($indice->idregistro==0) class="ubicado" @endif>
						<td>{{$indice->valor}}</td>
						<td>
							<select data-k="{{$indice->valor}}" class="form-control registro"></select>
						</td>
					</tr>
					@endforeach
				</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


@section('scripts')
<script src="{{ asset('public/js/selectizeb4.js') }}"></script>
@endsection

@section('documentready')
<?php
$cadena_opciones='';
$coma='';
foreach($opciones as $el_opcion){
	$cadena_opciones.=$coma.'{';
	$cadena_opciones.="'id':".$el_opcion->id."";
	$cadena_opciones.=",'value':'".addslashes($el_opcion->etiqueta)."'";
	$cadena_opciones.='}';
	$coma=',';
}
?>
var opciones=[<?php echo $cadena_opciones;?>];

var entidad='<?php echo $opcion;?>';

$(document).ready(function(){
  $('.registro').selectize({
      valueField: 'id',
      labelField: 'value',
      searchField: 'value',
      placeholder: 'Busqueda...',
      maxItems:1,
      create:false, 
      onChange:function(value){  
        el_dom=$(this)[0].$input[0];  
        entrada=$(el_dom).data('k');
        datos={
         _token:'{{csrf_token()}}'
        ,val:entrada
        ,idregistro:value
        ,entity:entidad
        };
        $.ajax({
                url: '{{action('ActivoController@save_indice_inverso')}}',
                data:datos, 
                type: 'POST',
                error: function(res) {  
                    console.log(res.responseText);
                },
                success: function(res) { 
                    console.log(res);
                    
                }
            });
             
      return true;
      },            
      options:opciones
  });
});




@endsection