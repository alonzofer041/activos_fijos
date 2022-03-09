@extends('admin3.app')


@section('scripts')
<script src="{{ asset('public/js/tmpl.min.js') }}"></script>
<script src="{{ asset('public/js/svg.min.js') }}"></script>
<script src="{{ asset('public/js/Visor_Mapa.js') }}"></script>
<script type="text/javascript" src="https://rawgit.com/DanielHoffmann/jquery-svg-pan-zoom/master/compiled/jquery.svg.pan.zoom.js"></script>
@endsection

@section('contenido')
<div class="row">
	<div class="col-md-12 col-xs-12 col-xs-12">
	<select class="form-control" id="sel_edificio">
		<option value="A">Edificio A</option>
		<option value="B">Edificio B</option>
		<option value="C">Edificio C</option>
		<option value="E">Edificio E</option>
		<option value="F">Edificio F</option>
		<option value="G">Edificio G</option>
		<option value="H">Edificio H</option>
		<option value="I">Edificio I</option>
		<option value="J">Edificio J</option>
		<option value="K">Edificio K</option>
		<option value="M">Edificio M</option>
		<option value="N">Edificio N</option>
		<option value="Q">Edificio Q</option>
		<option value="R">Edificio R</option>
		<option value="RH">Edificio RH</option>
		<option value="ST">Edificio ST</option>
		<option value="T">Edificio T</option>
		<option value="X">Edificio X</option>
		<option value="Z">Edificio Z</option>
	</select>
	</div>
</div>
<div style="position:relative">
    <div style="position: absolute;top:20px;left:30px;z-index:2;">
    	<div class="bg-white pd-20 box-shadow border-radius-5 height-100-p">
						<h4 class="mb-30">Activos por ubicar</h4>
						<div class="to-do-list mx-h-450 customscroll">
							<ul id="lista_activos">
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck1">
										<label class="custom-control-label" for="customCheck1">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck2">
										<label class="custom-control-label" for="customCheck2">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck3">
										<label class="custom-control-label" for="customCheck3">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck4">
										<label class="custom-control-label" for="customCheck4">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck5">
										<label class="custom-control-label" for="customCheck5">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck6">
										<label class="custom-control-label" for="customCheck6">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck7">
										<label class="custom-control-label" for="customCheck7">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck8">
										<label class="custom-control-label" for="customCheck8">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck9">
										<label class="custom-control-label" for="customCheck9">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck10">
										<label class="custom-control-label" for="customCheck10">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck11">
										<label class="custom-control-label" for="customCheck11">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck12">
										<label class="custom-control-label" for="customCheck12">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck13">
										<label class="custom-control-label" for="customCheck13">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck14">
										<label class="custom-control-label" for="customCheck14">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck15">
										<label class="custom-control-label" for="customCheck15">Check this custom checkbox</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="chck-activo custom-control-input" id="customCheck16">
										<label class="custom-control-label" for="customCheck16">Check this custom checkbox</label>
									</div>
								</li>
							</ul>
						</div>
					</div>
    </div>  
	@include('visor_mapa.mapa_utm2')
</div>

<script type="text/x-tmpl" id="fila-activo">
<li id="check_{%=o.idactivo%}">
	<div class="custom-control custom-checkbox">
		<input data-k="{%=o.idactivo%}" type="checkbox" class="chck-activo" value="{%=o.idactivo%}">
		<label class="custom-control-label" for="customCheck16">{%=o.nomactivo%}</label>
		<br/>
		<small>Ubicación: {%=o.ubicacion%}</small>
	</div>
</li>
</script>
@endsection

@section('documentready')
var anterior='';
$(document).ready(function(){
	$("#sel_edificio").bind('change',function(){
	   iden=$(this).val();
	   $("#edificio_"+iden).hide();
	   if(anterior!=''){
	     $("#edificio_"+anterior).show();
	   }
	   anterior=iden;
	});

	

	$("#lista_activos").on('click','.chck-activo',function(){
	    ide=$(this).data('k');	    
		Map_Manager.update_selected(ide);
	});

	config={
	  activos:[
	     {"idactivo":1,"nomactivo":'AASCGDF',"ubicacion":'A-12345'},
	     {"idactivo":2,"nomactivo":'BVCGDRET',"ubicacion":'B-12345'}
	   ]
	};
	Map_Manager.init(config);

	//$("#utm_mapa").svgPanZoom();
});
@endsection