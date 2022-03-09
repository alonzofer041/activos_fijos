<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Model\Activo;
use App\Model\Area;
use App\Model\Baja;
use App\Model\Desuso;
use App\Model\Detalle_Movimiento;
use App\Model\Detalle_Resguardo;
use App\Model\Garantia;
use App\Model\Identificacion;
use App\Model\Mantenimiento;
use App\Model\Movimiento;
use App\Model\Partida;
use App\Model\Prestamo;
use App\Model\Reparacion;
use App\Model\Resguardo;
use App\Model\Robo;
use App\Model\Tipo_Movimiento;
use App\Model\Traslado;
use App\Model\Ubicacion;
use App\Model\Usuario;
use App\PdfBitacora;

class BitacoraController extends Controller
{
    function lista(Request $r){
		$usuarios=Usuario::all();
		$activos=DB::table('activo')->join('detalle_resguardo','activo.idactivo','detalle_resguardo.idactivo')
		->join('resguardo','detalle_resguardo.idresguardo','resguardo.idresguardo')
		->join('identificacion','activo.ididentificacion','identificacion.ididentificacion')
		->select('detalle_resguardo.idactivo','identificacion.nombre','activo.clave_interna')
		
		->get();
		$areas=Area::all();
		$tiposmovimiento=Tipo_Movimiento::all();
		$reparaciones=Reparacion::all();
		$mantenimientos=Mantenimiento::all();
		$consulta=DB::table('movimiento')
		->join('tipo_movimiento','movimiento.id_tipo_movimiento','=','tipo_movimiento.idtipo_movimiento')
		->join('detalle_movimiento','movimiento.id_movimiento','=','detalle_movimiento.id_movimiento')
		->join('activo','detalle_movimiento.id_activo','=','activo.idactivo')
		->join('identificacion','activo.ididentificacion','=','identificacion.ididentificacion')
		->join('usuario','detalle_movimiento.id_emisor','=','usuario.idusuario')
		->leftJoin('usuario as usuario2','detalle_movimiento.id_receptor','usuario2.idusuario')
		->join('area','usuario.idarea','=','area.idarea')
		->select(
					'movimiento.id_movimiento',
					'movimiento.motivo',
					'movimiento.fecha_movimiento',
					'tipo_movimiento.nomtipomov',
					'usuario.nomusuario',
					'usuario.apellidos',
					'detalle_movimiento.area_gestora',
					'area.nomarea',
					'movimiento.id_tipo_movimiento',
					'detalle_movimiento.id_activo',
					'detalle_movimiento.id_detalle',
					'identificacion.nombre',
					'activo.clave_interna',
					'detalle_movimiento.cantidad',
					'detalle_movimiento.precio',
					'detalle_movimiento.id_receptor',
					'usuario2.nomusuario as nomusuariodestino',
					'usuario2.apellidos as apellidosdestino')
		->get();
		$datos=array();
		$datos['usuarios']=$usuarios;
		$datos['resguardos']=$activos;
		$datos['tiposmovimiento']=$tiposmovimiento;
		$datos['areas']=$areas;
		$datos['reparaciones']=$reparaciones;
		$datos['mantenimientos']=$mantenimientos;
		$datos['movimientos']=$consulta;
		// if ($r->isMethod("post")) {
		// 	$info=$r->all();
		// 	$dts=json_decode($info['texto']);
		// 	$fecha=$dts->fechalimite;
		// 	$datos['movimientos']=Movimiento::where('id_movimiento',$fecha)->get();
		// }
		// else{
		// }
		// $datos['movimientos']=$movimientos;
    	return view('bitacora.bitacora_listado')->with($datos);
	}
	function consultardetalles(Request $r){
		$info=$r->all();
		$dts=json_decode($info['detallesmovimiento']);
		$datos['detallesmovimiento']=array();
		// $consulta=DB::table('detalle_movimiento')
		// ->join('movimiento','detalle_movimiento.id_movimiento','movimiento.id_movimiento')
		// ->join('baja','movimiento.id_movimiento','baja.id_movimiento')
		// ->join('desuso','movimiento.id_movimiento','desuso.id_movimiento')
		// ->join('mantenimiento','movimiento.id_movimiento','mantenimiento.id_movimiento')
		// ->join('prestamo','movimiento.id_movimiento','prestamo.id_movimiento')
		// ->join('reparacion','movimiento.id_movimiento','reparacion.id_movimiento')
		// ->join('robo','movimiento.id_movimiento','robo.id_movimiento')
		// ->join('traslado','movimiento.id_movimiento','traslado.id_movimiento')
		// ->join('area','detalle_movimiento.area_gestora','area.idarea')
		// ->select(
		// 	'detalle_movimiento.clave',
		// 	'area.nomarea',
		// 	'detalle_movimiento.observaciones',
		// 	'detalle_movimiento.estado_activo'
		// );
		// switch ($dts->tipo) {
		// 	case 2:
		// 		$consulta->addSelect(
		// 			'mantenimiento.fecha_proximo',
		// 			'mantenimiento.costo',
		// 			'detalle_movimiento.fecha_termino'
		// 		);
		// 		break;
		// 	case 3:
		// 		$consulta->addSelect(
		// 			'detalle_movimiento.fecha_termino',
		// 			'reparacion.costo'
		// 		);
		// 		break;
		// 	case 5:
		// 		$consulta->addSelect(
		// 			'robo.lugar_robo',
		// 			'hora_robo'	
		// 		);
		// 		break;
		// }
		// $consulta->where('detalle_movimiento.id_detalle',$dts->detalle);
		$consulta=DB::table('detalle_movimiento')
		->join('area','detalle_movimiento.area_gestora','area.idarea')
		->join('movimiento','detalle_movimiento.id_movimiento','movimiento.id_movimiento')
		->join('activo','detalle_movimiento.id_activo','activo.idactivo')
		->join('identificacion','activo.ididentificacion','identificacion.ididentificacion');
		switch ($dts->tipo) {
			case 1:
				// $consulta->join('prestamo','movimiento.id_movimiento','prestamo.id_movimiento')
				$consulta->join('prestamo','detalle_movimiento.id_detalle','prestamo.id_movimiento')
				->join('usuario','usuario.idusuario','detalle_movimiento.id_receptor')
				->join('area as area2','detalle_movimiento.nueva_ubicacion','area2.idarea')
				->select(
						'detalle_movimiento.fecha_termino',
						'usuario.nomusuario',
						'usuario.apellidos',
						'detalle_movimiento.nueva_ubicacion',
						'area2.nomarea as area_destino'
					);
				break;
			case 2:
				// $consulta->join('mantenimiento','movimiento.id_movimiento','mantenimiento.id_movimiento')
				$consulta->join('mantenimiento','detalle_movimiento.id_detalle','mantenimiento.id_movimiento')
				->select(
						'mantenimiento.fecha_proximo',
						'mantenimiento.costo',
						'detalle_movimiento.fecha_termino'
					);
				break;
			case 3:
				// $consulta->join('reparacion','movimiento.id_movimiento','reparacion.id_movimiento')
				$consulta->join('reparacion','detalle_movimiento.id_detalle','reparacion.id_movimiento')
				->select(
					'reparacion.costo',
					'detalle_movimiento.fecha_termino'
				);
				break;
			case 4:
				// $consulta->join('traslado','movimiento.id_movimiento','traslado.id_movimiento')
				$consulta->join('traslado','detalle_movimiento.id_detalle','traslado.id_movimiento')
				->join('usuario','usuario.idusuario','detalle_movimiento.id_receptor')
				->join('area as area2','detalle_movimiento.nueva_ubicacion','area2.idarea')
				->select(
					'detalle_movimiento.nueva_ubicacion',
					'usuario.nomusuario',
					'usuario.apellidos',
					'area2.nomarea as area_destino'
				);
				break;
			case 5:
				// $consulta->join('robo','movimiento.id_movimiento','robo.id_movimiento')
				$consulta->join('robo','detalle_movimiento.id_detalle','robo.id_movimiento')
				->select(
						'robo.lugar_robo',
						'hora_robo'	
				);
				break;
			case 6:
				// $consulta->join('desuso','movimiento.id_movimiento','desuso.id_movimiento')
				$consulta->join('desuso','detalle_movimiento.id_detalle','desuso.id_movimiento')
				->select(
					'desuso.tiempo_inactividad',
					'detalle_movimiento.fecha_termino'	
				);
				break;
			case 7:
				// $consulta->join('baja','movimiento.id_movimiento','baja.id_movimiento')
				$consulta->join('baja','detalle_movimiento.id_detalle','baja.id_movimiento')
				->select(
					'baja.fecha_adquisicion',
					'baja.fecha_desuso',
					'detalle_movimiento.precio'	
				);
				break;
			case 8:
				// $consulta->join('garantia','movimiento.id_movimiento','garantia.id_movimiento')
				$consulta->join('garantia','detalle_movimiento.id_detalle','garantia.id_movimiento')
				->select(
					'garantia.fecha_compra',
					'garantia.proveedor',
					'garantia.municipio',
					'garantia.estado',
					'garantia.direccion',
					'garantia.cp'	
				);
				break;
		}
		$consulta->addSelect(
				'detalle_movimiento.id_detalle',
				'movimiento.id_tipo_movimiento',
				'detalle_movimiento.clave',
				'area.nomarea',
				'detalle_movimiento.observaciones',
				'detalle_movimiento.estado_activo',
				'identificacion.nombre'
			)
		->where('detalle_movimiento.id_detalle',$dts->detalle);
		$registros=$consulta->first();
		$datos['detallesmovimiento']=$registros;
		return response()->json([
			'data'=>$datos['detallesmovimiento']
		]);
	}
	function convertirpdf(){
		// $info=$r->all();
		$activos=Activo::join('detalle_resguardo','activo.idactivo','detalle_resguardo.idactivo')
		->join('resguardo','detalle_resguardo.idresguardo','resguardo.idresguardo')
		->select('detalle_resguardo.idactivo')
		->where('resguardo.idusuario',56)
		->get();
		$pdf=new PdfBitacora();
		$pdf->logoUTM=asset("public/images/logo_utm.png");
		//CAMBIAR DATOS
		$consulta=array();
		$consulta=DB::table("movimiento")
		->join("tipo_movimiento","movimiento.id_tipo_movimiento","tipo_movimiento.idtipo_movimiento")
		->join("detalle_movimiento",'movimiento.id_movimiento','detalle_movimiento.id_movimiento')
		->join("usuario",'detalle_movimiento.id_emisor','usuario.idusuario')
		->leftJoin("usuario as usuario2",'detalle_movimiento.id_receptor','usuario2.idusuario')
		->join("area",'detalle_movimiento.area_gestora','area.idarea')
		->leftJoin("area as area2",'detalle_movimiento.nueva_ubicacion','area2.idarea')
		->join("activo",'detalle_movimiento.id_activo','activo.idactivo')
		->join("identificacion",'activo.ididentificacion','identificacion.ididentificacion')
		// ->join("detalle_resguardo",'detalle_movimiento.id_activo','detalle_resguardo.idactivo')
		// ->join("resguardo",'detalle_resguardo.idresguardo','resguardo.idresguardo')
		->select(
			"tipo_movimiento.nomtipomov",
			"movimiento.motivo",
			"usuario.nomusuario as nombreusuario1",
			"identificacion.nombre",
			"activo.clave_interna",
			"detalle_movimiento.cantidad",
			"detalle_movimiento.precio",
			"detalle_movimiento.area_gestora",
			"area.nomarea",
			"usuario2.nomusuario as nombreusuario2",
			"area2.nomarea as area_destino",
			"movimiento.fecha_movimiento",
			"detalle_movimiento.fecha_termino");
		foreach($activos as $activo){
			$consulta->orWhere("detalle_movimiento.id_activo",$activo['idactivo']);
		}
		$pdf->movimientos=$consulta->get();
		$pdf->hacerPDF();
	}
	// function consulta(Request $r){
	// 	$info=$r->all();
	// 	$dts=json_decode($info['texto']);
	// 	$datos['movimientos']=array();
	// 	$consulta=DB::table('movimiento')
	// 	->join('tipo_movimiento','movimiento.id_tipo_movimiento','=','tipo_movimiento.idtipo_movimiento')
	// 	->join('detalle_movimiento','movimiento.id_movimiento','=','detalle_movimiento.id_movimiento')
	// 	->join('activo','detalle_movimiento.id_activo','=','activo.idactivo')
	// 	->join('identificacion','activo.ididentificacion','=','identificacion.ididentificacion')
	// 	->join('usuario','detalle_movimiento.id_emisor','=','usuario.idusuario')
	// 	->join('area','usuario.idarea','=','area.idarea')
	// 	->select(
	// 		'movimiento.id_movimiento',
	// 		'motivo',
	// 		'fecha_movimiento',
	// 		'tipo_movimiento.nomtipomov',
	// 		'usuario.nomusuario',
	// 		'usuario.apellidos',
	// 		'detalle_movimiento.area_gestora');
	// 	if($dts->fechalimite!=""){
	// 		$fecha=$dts->fechalimite;
	// 		switch($fecha){
	// 			case "mes":
	// 				$consulta->orWhere(DB::Raw("DATE_FORMAT(fecha_movimiento,'%m')"),'=',DB::RAW("DATE_FORMAT(NOW(),'%m')"));
	// 			break;
	// 			case "anio":
	// 				$consulta->orWhere(DB::Raw("DATE_FORMAT(fecha_movimiento,'%y')"),'<=',DB::RAW("DATE_FORMAT(NOW(),'%y')"));
	// 			break;
	// 			case "todos":
	// 				$consulta->get();
	// 			break;
	// 		}
	// 	}
	// 	if($dts->tipomovimientorealizado!=""){
	// 		$tipomovimiento=$dts->tipomovimientorealizado;
	// 		switch ($tipomovimiento) {
	// 			case 'todos':
	// 				$consulta->get();
	// 				break;
				
	// 			default:
	// 				$consulta->where('movimiento.id_tipo_movimiento',$tipomovimiento);
	// 				break;
	// 		}
	// 	}
	// 	if($dts->activor!=""){
	// 		$activo=$dts->activor;
	// 		switch ($activo) {
	// 			case 'todos':
	// 				$consulta->get();
	// 				break;
				
	// 			default:
	// 				$consulta->where('activo.idactivo',$activo);		
	// 				break;
	// 		}
	// 	}
	// 	if($dts->areapersona!=""){
	// 		$area=$dts->areapersona;
	// 		switch ($area){
	// 			case "todos":
	// 				$consulta->get();
	// 				break;
	// 			default:
	// 				$consulta->where('detalle_movimiento.area_gestora',$area);
	// 				break;
	// 		}
	// 	}
	// 	$registros=$consulta->get();
	// 	$datos['movimientos']=$registros;
	// 	return response()->json([
	// 		'data'=>$datos['movimientos']
	// 	]);
	// }
	//=================================== PARTE DE MIS ACTIVOS =============================================//

	function misactivos(){
		$activos=Activo::join('identificacion','activo.ididentificacion','identificacion.ididentificacion')
		->join('partida','activo.idpartida','partida.idpartida')
		->join('detalle_resguardo','activo.idactivo','detalle_resguardo.idactivo')
		->join('resguardo','detalle_resguardo.idresguardo','resguardo.idresguardo')
		->where('resguardo.idusuario',56)
		->select('identificacion.marca','identificacion.factura','identificacion.modelo','partida.recurso','identificacion.total','activo.razon_social','resguardo.idusuario','identificacion.nombre','resguardo.no_resguardo')
		
		->get();
		$resguardos=Resguardo::join('usuario','resguardo.idusuario','usuario.idusuario')
		->select('usuario.nomusuario',
		'usuario.apellidos',
		'resguardo.fecha',
		'resguardo.no_resguardo',
		'resguardo.idusuario',
		'resguardo.idresguardo')
		->where('resguardo.idusuario','=',56)
		->get();
		$datos=array();
		$datos['activos']=$activos;
		$datos['resguardos']=$resguardos;
		return view('bitacora.misactivos')->with($datos);
	}
	// function buscaractivosresguardo(Request $r){
	// 	$info=$r->all();
	// 	$dts=json_decode($info['texto']);
	// 	$consulta=DB::table('activo')
	// 	->join('identificacion','activo.ididentificacion','=','identificacion.ididentificacion')
	// 	->join('detalle_resguardo','activo.idactivo','=','detalle_resguardo.idactivo')
	// 	->join('resguardo','detalle_resguardo.idresguardo','resguardo.idresguardo')
	// 	->select(
	// 		'identificacion.marca',
	// 		'identificacion.modelo',
	// 		'identificacion.factura',
	// 		'activo.razon_social',
	// 		'resguardo.no_resguardo',
	// 		'identificacion.total'
	// 	)
	// 	->where('resguardo.no_resguardo',$dts->numeroresguardo)->get();
	// 	$datos=array();
	// 	$datos['activoxresguardo']=$consulta;
	// 	return response()->json([
	// 		'data'=>$datos['activoxresguardo']
	// 	]);
	// }
    //GENERADOR FAKER
    function crear_datos(){
    	date_default_timezone_get('America/Merida');
    	$tipos_movimiento=Tipo_Movimiento::all();
    	$activos=Activo::all();
    	$numactivos=sizeof($activos);
		$usuarios=Usuario::all();
		$ubicaciones=Ubicacion::all();
		$num_ubicaciones=sizeof($ubicaciones)-1;
    	$num_usuarios=sizeof($usuarios)-1;
		$num_tipos_movimiento=sizeof($tipos_movimiento);
    	$contador=0;
    	$faker=Faker::create();

    	for ($i=1; $i <= 70; $i++) { 
    		$movimiento=new Movimiento();
			$movimiento->motivo=$faker->realText(255,2);
			$dt=$faker->dateTimeBetween('-1 years','now');
    		$movimiento->fecha_movimiento=$dt->format('Y-m-d');
    		$movimiento->id_tipo_movimiento=$faker->numberBetween(1,$num_tipos_movimiento);
			$movimiento->save();
			$numero_detalles=$faker->numberBetween(1,10);
			for($i=1; $i <= $numero_detalles; $i++){
				$indice_usuario=$faker->numberBetween(0,$num_usuarios);
				$indice_usuario_receptor=$faker->numberBetween(0,$num_usuarios);
				$indice_movimiento=$movimiento->id_tipo_movimiento-1;
				$indice_activo=$faker->numberBetween(0,$numactivos-1);
				
				$detalle_movimiento=new Detalle_Movimiento();
				$detalle_movimiento->descripcion_motivo=$faker->realText(400,2);
				$detalle_movimiento->clave=substr($tipos_movimiento[$indice_movimiento]['nomtipomov'], 2).$faker->unique()->postcode();
				$detalle_movimiento->area_gestora=$usuarios[$indice_usuario]->idarea;
				$detalle_movimiento->aprobacion=$faker->boolean();
				$detalle_movimiento->observaciones=$faker->realText(255,2);
				$detalle_movimiento->id_activo=$activos[$indice_activo]->idactivo;
				$identificacion=Identificacion::find($detalle_movimiento->id_activo);
				$detalle_movimiento->precio=$identificacion->total;
				$detalle_movimiento->cantidad=$faker->numberBetween(1,$identificacion->cantidad);
				$detalle_movimiento->id_emisor=$usuarios[$indice_usuario]->idusuario;
				$detalle_movimiento->id_movimiento=$movimiento->id_movimiento;
				$detalle_movimiento->estado_activo=$faker->numberBetween(1,3);
				$detalle_movimiento->save();
				switch ($movimiento->id_tipo_movimiento) {
					case '1':
						$tiempo=$faker->numberBetween(1,31);
						$detalle_movimiento->id_receptor=$usuarios[$indice_usuario_receptor]->idusuario;
						$detalle_movimiento->fecha_termino=date('Y-m-d',strtotime($movimiento->fecha_movimiento." +".$tiempo."days"));
						$detalle_movimiento->nueva_ubicacion=$usuarios[$indice_usuario_receptor]->idarea;
						$prestamo=new Prestamo();
						$prestamo->iddetallemov=$detalle_movimiento->id_detalle;
						$prestamo->save();
						$detalle_movimiento->save();
						break;
					case '2':
						$tiempo=$faker->numberBetween(1,31);
						$detalle_movimiento->id_receptor=NULL;
						$detalle_movimiento->fecha_termino=date('Y-m-d',strtotime($movimiento->fecha_movimiento." +".$tiempo."days"));
						$detalle_movimiento->nueva_ubicacion=NULL;
						$mantenimiento=new Mantenimiento();
						// do{
						// 	$mantenimiento->fecha_limite=$faker->date('Y-m-d','2020-06-24');
						// 	$fechamant=strtotime($mantenimiento->fecha_limite);
						// }
						// while($fechamant<=strtotime(date('Y-m-d')));
						$mantenimiento->fecha_proximo=date('Y-m-d',strtotime("+30days"));
						$mantenimiento->costo=$faker->randomFloat(NULL,0,200);
						$mantenimiento->iddetallemov=$detalle_movimiento->id_detalle;
						$mantenimiento->save();
						$detalle_movimiento->save();
						break;
					case '3':
						$tiempo=$faker->numberBetween(1,31);
						$detalle_movimiento->id_receptor=NULL;
						$detalle_movimiento->fecha_termino=date('Y-m-d',strtotime($movimiento->fecha_movimiento." +".$tiempo."days"));
						$detalle_movimiento->nueva_ubicacion=NULL;

						$reparacion=new Reparacion();
						$reparacion->costo=$faker->randomFloat(NULL,0,200);
						$reparacion->iddetallemov=$detalle_movimiento->id_detalle;
						$reparacion->save();
						$detalle_movimiento->save();
						break;
					case '4':
						$detalle_movimiento->id_receptor=$usuarios[$indice_usuario_receptor]->idusuario;
						$detalle_movimiento->fecha_termino=NULL;
						$detalle_movimiento->nueva_ubicacion=$usuarios[$indice_usuario_receptor]->idarea;
						$traslado=new Traslado();
						$traslado->iddetallemov=$detalle_movimiento->id_detalle;
						$traslado->save();
						$detalle_movimiento->save();
						break;
					case '5':
						$indice_ubicacion=$faker->numberBetween(0,$num_ubicaciones);
						$tiempo_robo=$faker->dateTimeBetween("-1 years",'now');
						$detalle_movimiento->id_receptor=NULL;
						$detalle_movimiento->fecha_termino=NULL;
						$detalle_movimiento->nueva_ubicacion=NULL;
						$robo=new Robo();
						$robo->lugar_robo=$ubicaciones[$indice_ubicacion]->descripcion;
						$robo->hora_robo=$tiempo_robo->format('H:i:s');
						$robo->iddetallemov=$detalle_movimiento->id_detalle;
						$robo->save();
						$detalle_movimiento->save();
						break;
					case '6':
						$tiempo=$faker->numberBetween(1,31);
						$detalle_movimiento->id_receptor=NULL;
						$detalle_movimiento->fecha_termino=date('Y-m-d',strtotime($movimiento->fecha_movimiento." +".$tiempo."days"));
						$detalle_movimiento->nueva_ubicacion=NULL;
						$desuso=new Desuso();
						$desuso->tiempo_inactividad=$faker->numberBetween(1,31)."días";
						$desuso->iddetallemov=$detalle_movimiento->id_detalle;
						$desuso->save();
						$detalle_movimiento->save();
						break;
					case '7':
						$tiempo=$faker->numberBetween(1,31);
						$fecha_compra=$faker->dateTimeBetween('2000-01-01','2016-12-30');
						$detalle_movimiento->id_receptor=NULL;
						$detalle_movimiento->fecha_termino=NULL;
						$detalle_movimiento->nueva_ubicacion=NULL;
						$baja=new Baja();
						$baja->fecha_adquisicion=$fecha_compra->format('Y-m-d');
						$baja->fecha_desuso=date('Y-m-d',strtotime($movimiento->fecha_movimiento." +".$tiempo."days"));
						$baja->iddetallemov=$detalle_movimiento->id_detalle;
						$baja->save();
						$detalle_movimiento->save();
						break;
					case '8':
						$tiempo=$faker->numberBetween(1,3);
						$fecha_compra=$faker->dateTimeBetween('2000-01-01','2016-12-30');
						$detalle_movimiento->fecha_termino=date('Y-m-d',strtotime($movimiento->fecha_movimiento." +".$tiempo."years"));
						$detalle_movimiento->nueva_ubicacion=NULL;
						$detalle_movimiento->id_receptor=NULL;
						$garantia=new Garantia();
						$garantia->proveedor=$faker->company();
						$garantia->fecha_compra=$fecha_compra->format('Y-m-d');
						$garantia->municipio=$faker->city();
						$garantia->estado=$faker->state();
						$garantia->direccion=$faker->address();
						$garantia->cp=$faker->postcode();
						$garantia->iddetallemov=$detalle_movimiento->id_detalle;
						$garantia->save();
						$detalle_movimiento->save();
						break;
				}

			}
    	}
    }


}
