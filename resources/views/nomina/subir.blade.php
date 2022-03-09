@extends('admin3.app')

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


@section('contenido')
<div class="row">
	<div class="col-md-12 col-xs-12 col-xs-12">
		<div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
		    <div class="clearfix mb-20">
				<div class="pull-left">
					<h4 class="text-blue">Panel de Activos</h4>				
				</div>
			</div>
			<form id="alta_activo" action="{{action('NominaController@procesa_nomina')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
		      {{ csrf_field() }}
		      <div class="row">
		        <div class="col-md-12 col-xs-12 col-sm-12">
		      <div class="form-group">
		        <label class="form-label">Archivo</label>
		        <input type="file" class="form-control" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="zip_nomina" value="">
		      </div>    
		        </div>
		      </div>
		      <button type="submit" class="btn btn-success">Enviar</button>
		      
		    </form>
		</div>
	</div>
</div>
@endsection