@extends('admin3.app')
@section('estilos')
.contenedores{
    margin-bottom:10px;
    border-left:5px solid #0099FF;
}
.dropdown-menu{
	position:relative;
	width:400px;
	border-left:5px solid #0099FF;
}
.dropdown-menu > .dropdown-item{
	margin-left:20px;
	color:#000;
}
.textotablas{
	font-size:13px;
}
.espacio{
	margin-right:4px;
}
@endsection
@section('contenido')
	<div id="aplicacion" class="container">
		<div class="row">
			<div class="col-xs-8 col-md-8">
				<div class="bg-white pd-30  box-shadow border-radius-5 xs-pd-20-10 height-100-p">
				<h2 class="example-popover"></h2>
				<h4 class="text-blue">Resúmen de movimientos</h4>
					<!-- FILTROS DE TABLA DE MOVIMIENTO -->
					<form action="" id="formulario" v-on:change="" method="">
						{{csrf_field()}}
						
						<div class="row">
         <div class="col-xs-12 col-md-3">
 			<div class="form-group">
 				<label for="">Fecha de movimiento:</label>
 				<select name="fecha" id="" class="form-control" v-model="fecha">
 					<option value="<?php echo date('m')?>">Ultimo mes</option>
 					<option value="<?php echo date('Y')?>">Ultimo año</option>
					<option value="todos">Todos</option>
 				</select>
 			</div>
 		</div>

		 <div class="col-xs-12 col-md-3">
 			<div class="form-group">
 				<label for="">Activos:</label>
				<input list="activos" type="text" class="form-control" v-model.lazy="activo">
				<datalist id="activos">
					<option v-for="activo in activos" :value="activo.clave_interna">@{{activo.nombre}} @{{activo.clave_interna}}</option>
					<option value="todos">Todos los activos</option>
				</datalist>
 			</div>
 		</div>
		
 		<div class="col-xs-12 col-md-3">
 			<div class="form-group">
 				<label>Area:</label>
				<input list="areas" name="areas" type="text" class="form-control" v-model.lazy="area">
 				
				<datalist id="areas">
					<option v-for="area in areas" :value="area.nomarea"></option>
					<option value="todos">Todas las áreas</option>
				</datalist>
 			</div>
 		</div>
 		<div class="col-xs-12 col-md-3">
 			<div class="form-group">
 				<label for="">Tipo de movimiento:</label>
				<input type="text" class="form-control" list="tipos" v-model.lazy="tipo_movimiento">
				<datalist id="tipos">
					<option v-for="tipo in tipos" :value="tipo.nomtipomov"></option>
					<option value="todos">Todos los movimientos</option>
				</datalist>
 			</div>
 		</div>
     </div>
						
					</form>
					<!-- TABLA DE MOVIMIENTOS -->
					<a href="{{action('BitacoraController@convertirpdf')}}" target="blank" class="btn btn-primary"><span class="fa fa-print espacio"></span>Imprimir a PDF</a>
					<a href="{{action('MovimientoController@listamovs')}}" class="btn btn-success">Agregar movimiento</a>
					<div class="table-responsive">
						<table class="table">
							<tr>
								<td>Activo</td>
								<td>Movimiento</td>
								<td>Destino</td>
								<td>Motivo</td>
								<td>Detalles</td>
								<!-- <td>Detalles del movimiento</td> -->
							</tr>
							<tr v-for="texto in filas">
		 			            <td class="textotablas">
								 	@{{texto.nombre}}
									<br>
									Clave: @{{texto.clave_interna}}
									Cantidad: @{{texto.cantidad}}
									Precio: $@{{texto.precio}}
								</td>
								 <td class="textotablas">
								 	@{{texto.nomtipomov}}
									<br>
									@{{texto.fecha_movimiento | fecha_formato}}
									<br>
									Por: @{{texto.nomusuario}}
								 	<!-- <a href="#">consultar detalles del activo</a>
									<div class="dropdown"> 
  										<button class="btn btn-secondary dropdown-toggle"
										type="button" 
										id="dropdownMenu2" 
										data-toggle="dropdown" 
										aria-haspopup="true" 
										aria-expanded="false">
										Ver mas
  										</button>
  										<div class="dropdown-menu">
										  <a href="#" class="dropdown-item"><h3>Activo tal</h3></a>
  											<span class="dropdown-item">
											  	<strong>Marca</strong>: <span>AAasmdka</span> <br>
											  	<strong>Modelo</strong>: Modelo tal <br>
											  	<strong>Valor del activo</strong>: $999.00 <br>
												<strong>Ubicación actual:</strong>
											</span>
										</div>
									</div> -->
								 </td>
								 <td class="textotablas" v-if="texto.id_receptor!=null">@{{texto.nomusuariodestino}} @{{texto.apellidosdestino}}</td>
								 <td class="textotablas" v-else>No aplica</td>
								 <td style="font-size:13px;">
								 	<div v-if="ver">
									 	@{{texto.motivo}}
								 		<a style="font-size:16px;" href="#" v-on:click="vermas">Ver menos</a>
									</div>
									<div v-else>
										@{{texto.motivo | subStr}}
										<a style="font-size:16px;" href="#" v-on:click="vermas">Ver mas</a>
									</div>
									<td>
									<form action="">
										{{csrf_field()}}
										<button class="btn btn-success" type="button" v-on:click="detallesmovimiento(texto.id_detalle,texto.id_tipo_movimiento)"><span class="fa fa-info-circle espacio"></span>Ver detalles</button>
										<!-- <input type="submit" v-model="texto.id_detalle">Ver detalles -->
									</form>
									</td>
								 </td>
								 <!-- <td>
									<button v-on:click="consultar(texto.id_movimiento,texto.id_tipo_movimiento)" class="btn btn-default">consultar</button>
								 	<div v-if="id_tipo_movimiento==2">
										<p><strong>Fecha proximo:</strong>@{{detalles_movimiento.fecha_proximo}}</p>
										<p><strong>Costo mantenimiento:</strong>@{{detalles_movimiento.costo}}</p> 
									</div>
								 	<div v-else-if="id_tipo_movimiento==3">
										<p><strong>Costo reparación: </strong>@{{detalles_movimiento.costo}}</p>
									</div>
								 </td> -->
         					</tr>
							
						</table>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-md-4">
				<div v-if="verdetalle" class="bg white pd-30 boxshadow border-radius-5 xs-pd-20-10 height-100-p">
					<a href="#" class="contenedores lt-item list-group-item flex-column align-items-start">
						<div class="d-flex w-100 justify-content-between">
							<h4 class="text-blue mb-1">@{{detallesmovimientos.nombre}}</h4>
						</div>
						<p class="mb-1">
							<h5>Detalles específicos</h5>
							<div v-if="detallesmovimientos.id_tipo_movimiento==1">
								<strong>Fecha de finalizacion:</strong> @{{detallesmovimientos.fecha_termino | fecha_formato}} <br>
								<strong>Nueva ubicación:</strong> @{{detallesmovimientos.area_destino}}
							</div>
							<div v-else-if="detallesmovimientos.id_tipo_movimiento==2">
								<strong>Fecha del próximo mantenimiento:</strong> @{{detallesmovimientos.fecha_proximo | fecha_formato}} <br>
								<strong>Costo del mantenimiento:</strong> $@{{detallesmovimientos.costo}} <br>	
								<strong>Fecha de finalización:</strong> @{{detallesmovimientos.fecha_termino | fecha_formato}} <br>
							</div>
							<div v-else-if="detallesmovimientos.id_tipo_movimiento==3">
								<strong>Fecha de finalizacion:</strong> @{{detallesmovimientos.fecha_termino | fecha_formato}} <br>
								<strong>Costo:</strong> $@{{detallesmovimientos.costo}}
							</div>
							<div v-else-if="detallesmovimientos.id_tipo_movimiento==4">
								<strong>Nueva ubicación:</strong> @{{detallesmovimientos.area_destino}}
							</div>
							<div v-else-if="detallesmovimientos.id_tipo_movimiento==5">
								<strong>Lugar del robo:</strong> @{{detallesmovimientos.lugar_robo}} <br>
								<strong>Hora del robo:</strong> @{{detallesmovimientos.hora_robo}} <br>	
							</div>
							<div v-else-if="detallesmovimientos.id_tipo_movimiento==6">
								<strong>Fecha de finalizacion:</strong> @{{detallesmovimientos.fecha_termino | fecha_formato}} <br>
								<strong>Tiempo de inactividad:</strong> @{{detallesmovimientos.tiempo_inactividad}}
							</div>
							<div v-else-if="detallesmovimientos.id_tipo_movimiento==7">
								<strong>Fecha de adquisición:</strong> @{{detallesmovimientos.fecha_adquisicion | fecha_formato}} <br>
								<strong>Fecha de desuso:</strong> @{{detallesmovimientos.fecha_desuso | fecha_formato}} <br>
								<strong>Precio del activo:</strong> $@{{detallesmovimientos.precio}}
							</div>
							<div v-else-if="detallesmovimientos.id_tipo_movimiento==8">
								<strong>Fecha de adquisición:</strong> @{{detallesmovimientos.fecha_compra | fecha_formato}} <br>
								<strong>Proveedor:</strong> @{{detallesmovimientos.proveedor}} <br>
								<strong>Municipio:</strong> @{{detallesmovimientos.municipio}} <br>
								<strong>Estado:</strong> @{{detallesmovimientos.estado}} <br>
								<strong>Direccion:</strong> @{{detallesmovimientos.direccion}} <br>
								<strong>Código postal:</strong> @{{detallesmovimientos.cp}} <br>
							</div>
						</p>
					</a>
				</div>
			</div>

		</div>
	</div>
@endsection
@section('vuejs')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
//PARTE VUE
<?php
	$cadenamovimientos="";
	$coma="";
	foreach($movimientos as $indice => $movimiento){
		$cadenamovimientos.=$coma.json_encode($movimiento);
		$coma=",";
	}
	
	$cadenaactivos="";
	$coma="";
	foreach($resguardos as $indice => $activo){
		$cadenaactivos.=$coma.json_encode($activo);
		$coma=",";
	}

	$cadenatipos="";
	$coma="";
	foreach($tiposmovimiento as $indice => $tipo){
		$cadenatipos.=$coma.json_encode($tipo);
		$coma=",";
	}

	$cadenaareas="";
	$coma="";
	foreach($areas as $indice => $area){
		$cadenaareas.=$coma.json_encode($area);
		$coma=",";
	}

	$cadenamantenimientos="";
	$coma="";
	foreach($mantenimientos as $indice => $mantenimiento){
		$cadenamantenimientos.=$coma.json_encode($mantenimiento);
		$coma=",";
	}

	$cadenareparaciones="";
	$coma="";
	foreach($reparaciones as $indice => $reparacion){
		$cadenareparaciones.=$coma.json_encode($reparacion);
		$coma=",";
	}
?>
var vm=new Vue({
	el:"#aplicacion",
	data:{
			fecha:"",
            area:"",
			activo:"",
			tipo_movimiento:"",
			id_movimiento:"",
			id_tipo_movimiento:"",
			id_detalle_movimiento:"",
			ver:false,
			activos:[
				<?php echo $cadenaactivos;?>
			],
			detallesmovimientos:[],
			tipos:[
				<?php echo $cadenatipos;?>
			],
			areas:[
				<?php echo $cadenaareas;?>
			],
			movimientos:[
				<?php echo $cadenamovimientos;?>
			],
			reparaciones:[
				<?php echo $cadenareparaciones;?>
			],
			mantenimientos:[
				<?php echo $cadenamantenimientos;?>
			],
			verdetalle:false
    },
    computed:{
		filas:function(){
			var filtro=[
				this.area.toLowerCase(),
				this.tipo_movimiento,
				this.activo,
				this.fecha
			];
			var band_area=false;
			var band_tipo_movimiento=false;
			var band_activo=false;
			var band_fecha=false;
			return this.movimientos.filter(function(item){
				if(filtro[0]==""){
					band_area=true;
				}
				else{
					if(filtro[0]==item.nomarea.toLowerCase()){
						band_area=true;
					}
					else if(filtro[0]=="todos"){
						band_area=true;
					}
					else{
						band_area=false;
					}
				}
				if(filtro[1]==""){
					band_tipo_movimiento=true;
				}
				else{
					if(filtro[1]==item.nomtipomov){
						band_tipo_movimiento=true;
					}
					else if(filtro[1]=="todos"){
						band_tipo_movimiento=true;
					}
					else{
						band_tipo_movimiento=false;
					}
				}
				if(filtro[2]==""){
					band_activo=true;
				}
				else{
					if(filtro[2]==item.clave_interna){
						band_activo=true;
					}
					else if(filtro[2]=="todos"){
						band_activo=true;
					}
					else{
						band_activo=false;
					}
				}
				if(filtro[3]==""){
					band_fecha=true;
				}
				else{
					if(filtro[3]==item.fecha_movimiento.substring(5,7)){
						band_fecha=true;
					}
					else if(filtro[3]==item.fecha_movimiento.substring(0,4)){
						band_fecha=true;
					}
					else if(filtro[3]=="todos"){
						band_fecha=true;
					}
					else{
						band_fecha=false;
					}
				}

				if((band_activo===true) && (band_tipo_movimiento===true) && (band_area===true) && (band_fecha===true)){
					return true;
				}
				else{
					return false;
				}
					
					// if(item.clave_interna==filtro[2] && 
					// (filtro[1]=="" || item.nomtipomov==filtro[1]) && 
					// (item.nomarea.toLowerCase()==filtro[0] || filtro[0]=="") &&
					// (item.fecha_movimiento.substring(5,7)==filtro[3] || filtro[3]=="" || item.fecha_movimiento.substring(0,4)==filtro[3])){
					// 	return true;
					// }
					// if(item.nomarea.toLowerCase()==filtro[0] && 
					// (filtro[1]=="" || item.nomtipomov==filtro[1]) && 
					// (filtro[2]=="" || item.clave_interna==filtro[2]) &&
					// (item.fecha_movimiento.substring(5,7)==filtro[3] || filtro[3]=="" || item.fecha_movimiento.substring(0,4)==filtro[3])){
					// 	return true;
					// }
					// if(item.nomtipomov==filtro[1] && 
					// (item.nomarea.toLowerCase()==filtro[0] || filtro[0]=="") && 
					// (filtro[2]=="" || item.clave_interna==filtro[2]) &&
					// (item.fecha_movimiento.substring(5,7)==filtro[3] || filtro[3]=="" || item.fecha_movimiento.substring(0,4)==filtro[3])){
					// 	return true;
					// }
					// if(item.fecha_movimiento.substring(5,7)==filtro[3] && 
					// (item.nomarea.toLowerCase()==filtro[0] || filtro[0]=="") && 
					// (filtro[2]=="" || item.clave_interna==filtro[2]) &&
					// (item.nomtipomov==filtro[1] || filtro[1]=="")){
					// 	return true;
					// }
					
				
			});
		},
		detalles:function(){
			var dt={
				detalle:this.id_detalle_movimiento,
				tipo:this.id_tipo_movimiento
			};
			detallesmov=JSON.stringify(dt);
			return detallesmov;
		}
		// detalles_movimiento:function(){
		// 	var idmov=this.id_movimiento;
		// 	var idtipo=this.id_tipo_movimiento;
		// 	switch (idtipo) {
		// 		case 3:
		// 			// return this.comparacion_tipos(this.mantenimientos,idmov);
		// 			return this.reparaciones.filter(function(item){
		// 				if(idmov==item.id_movimiento){
		// 					return true;
		// 				}
		// 				else{
		// 					return false;
		// 				}
		// 			});
		// 			break;
			
				// case 3:
				// 	return this.comparacion_tipos(this.reparaciones,idmov);
				// 	break;
			// }
		// }
		
        // contenidos:function(){
        //     var dt={
		// 			fechalimite:this.fecha,
		// 			areapersona:this.area,
		// 			tipomovimientorealizado:this.tipo_movimiento,
		// 			activor:this.activo
		// 		};
		// 		contenidos=JSON.stringify(dt);
		// 		return contenidos;
		// }

	},
	filters:{
		subStr:function(cadena){
			return cadena.substr(0,100) + "...";
		},
		fecha_formato:function(fecha_registro){
			var dias=['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];
			var meses=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
			fecha=new Date(fecha_registro);
			mes=meses[fecha.getUTCMonth()];
			dia=dias[fecha.getUTCDay()];
			anio=fecha.getUTCFullYear();
			fecha_num=fecha.getUTCDate();
			return dia + " " + fecha_num + " de " + mes + " de " + anio;
		}
	},
	methods:{
		
		comparacion_tipos(arreglo,idmovimiento){
			return arreglo.filter(function(item){
				if(idmovimiento==item.id_tipo_movimiento){
					return true;
				}
				else{
					return false;
				}
			});
		},
		vermas(){
			if(this.ver==false){
				this.ver=true;
			}
			else{
				this.ver=false;
			}
		},
		detallesmovimiento(iddetalle,idtipo){
			this.id_detalle_movimiento=iddetalle;
			this.id_tipo_movimiento=idtipo;
			const params={
				detallesmovimiento:this.detalles
			};
			axios.post("consultardetalles",params)
			.then(response=>{
				this.detallesmovimientos=response.data.data;
			})
			.catch(error=>{});
			this.verdetalle=true;
		}
	}
		
});
</script>
@endsection
