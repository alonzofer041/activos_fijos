<?php
use App\Model\BusinessObjectTickets;
use App\Model\Cortecaja;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Hashids\Hashids;


function pad_key($key){
	if(strlen($key)>32) return false;
	$sizes=array(16,24,32);
	foreach($sizes as $s){
		while(strlen($key)<$s) $key=$key."\0";
		if(strlen($key)==$s) break;
	}
	return $key;
}



function dame_configuracion(){
	return \DB::table('configuracion')->first();
}

function listarPermisosUsuario($idusuario,$sistema=1){
  $permisos_todos=DB::table('permiso')
                        ->join('modulo','permiso.idmodulo','=','modulo.idmodulo')
                        ->select(
                                'permiso.nompermiso'
                               ,'permiso.idpermiso'                               
                               ,'permiso.url'
                               ,'modulo.idmodulo'
                               ,'modulo.nommodulo'
                               ,'modulo.icono'
                               ,DB::Raw("0 as 'asignado'")
                                )                        
                        ->orderby('modulo.orden')
                        ->orderby('permiso.idmodulo')
                        ->get();
      $usuario=DB::table('usuario')->where('idusuario',$idusuario)->first();
      for($i=0;$i<count($permisos_todos);$i++){
        $tiene_permiso=DB::table('rolxpermiso')
                          ->where('idrol',$usuario->idrol)
                          ->where('idpermiso',$permisos_todos[$i]->idpermiso)
                          ->first();
        if($tiene_permiso){
          $permisos_todos[$i]->asignado=1;
        }
      }
  $final=array();
  foreach($permisos_todos as $elemento){
    $llave_modulo=$elemento->idmodulo;
    if(!array_key_exists($llave_modulo,$final)){
      $tmp=new \StdClass();  
      $tmp->nommodulo=$elemento->nommodulo;
      $tmp->idmodulo=$elemento->idmodulo;
      $tmp->icono=$elemento->icono;
      $tmp->permitidos=0;
      $tmp->permisos=array();
      $final[$llave_modulo]=$tmp;
    }

    if($elemento->asignado==1){
      $final[$llave_modulo]->permitidos++;
      $tmp2=new \StdClass();
      $tmp2->idpermiso=$elemento->idpermiso; 
      $tmp2->nompermiso=$elemento->nompermiso;      
      $tmp2->url=$elemento->url;
      $final[$llave_modulo]->permisos[]=$tmp2;
    }
  }
  return $final;
}

function action_exists($action) {
    try {
        action($action);
    } catch (\Exception $e) {
        return false;
    }

    return true;
}

function revierte_hashids($valor,$codigo='utm2018'){
    $hasids2= new Hashids($codigo);
	$tmp=$hasids2->decode($valor);
	if(count($tmp)!=0){
		return $tmp[0];
	}
	else
		return false;
}
function genera_hashids($valor,$codigo='utm2018'){
	$hashids = new Hashids($codigo);         
    $codigo=$hashids->encode($valor);
    return $codigo;
}

function define_carrito_sesion(){
	//Preguntar si ya existe un carrito en sesion
	if(!Session::has('icrrt')){
		//verificar si el usuario ya ha iniciado sesion
		if(Session::has('idusuario_eticket')){
			//recuperar el carrito con del usuario mas reciente
			$carrito=DB::table('carrito')
			           ->where('idusuario',Session::get('idusuario_eticket'))
			           ->where("status",1)
			           ->orderby('fecha_creacion','desc')
			           ->first();

			if($carrito){
				$ahora = date("Y-m-d H:i:s");
				if($ahora<=$carrito->fecha_cierre){
					//El carrito todavia es valido
					session(['icrrt' => $carrito->idcarrito]);
				}
			}
		}
	}
	else{
		$carrito=DB::table('carrito')
			           ->where('idcarrito',Session::get('icrrt'))			           
			           ->first();
	    if($carrito->status!=1){
	    	Session::forget('icrrt');
	    }
	}
}

//2018-02-22T12:39:00
function genera_nombre($sec,$fila,$numero){
  //nivel,ub1,ub2
  if($sec=='Luneta'){  
  $strub1='Fila: '.$fila;  
  $strub2='Asiento: '.str_replace($fila,'',$numero);
  }  
  else{  
  $strub1='Palco: '.$fila;
  $strub2='Asiento: '.str_replace($fila,'',$numero);
  }

  return array(
  	"seccion"=>$sec,
  	"ub1"=>$strub1,
  	"ub2"=>$strub2,
  	      );

}

function dame_detalle_carrito($idcarrito=0){
	if($idcarrito==0){
		$idcarrito=session()->get('icrrt');		
	}
	$bts_db=\DB::table('detalle_carrito')            
            ->join('butacaxprograma','butacaxprograma.idbutacaxzona','=','detalle_carrito.idbutacafuncion')
            ->join('butaca','butaca.idbutaca','=','butacaxprograma.idbutaca')
            ->join('seccion','seccion.idseccion','=','butaca.idseccion')
            ->join('programa','butacaxprograma.idprograma','=','programa.idprograma')
            ->join('pr_evento','programa.idevento','=','pr_evento.idprevento')
            ->join('zona','zona.idzona','=','butacaxprograma.idzona')
            ->select(
                     'butaca.idbutaca'
                     ,'butaca.numbutaca'
                     ,'butaca.fila'
                     ,'detalle_carrito.precio'
                     ,'butacaxprograma.idprograma'                         
                     ,'butacaxprograma.idbutacaxzona'                         
                     ,'pr_evento.nomevento'
                     ,'pr_evento.numevento'
                     ,'nomseccion'
                     ,'zona.color as color'
                     ,\DB::Raw("DATE_FORMAT(programa.fecha_inicio,'%Y-%m-%d') as fecha_inicio")
                    )                
            ->where('idcarrito', '=',$idcarrito)
            ->get();
    $datos=array();
    foreach ($bts_db as $butaca) {
    	$tmp=new \StdClass();
    	$tmp->nomevento=$butaca->nomevento;
    	$dts=genera_nombre($butaca->nomseccion,$butaca->fila,$butaca->numbutaca);
    	$tmp->nombutaca=$dts['seccion'].' | '.$dts['ub1'].' '.$dts['ub2'];    	
    	$tmp->funcion=formato_fecha($butaca->fecha_inicio,0,2);
    	$tmp->precio=$butaca->precio;
    	$datos[]=$tmp;
    }
    return $datos;
}

function dame_carrito($idcarrito){
	$carrito=DB::table('carrito')
	              ->select('idcarrito'
	              	      ,'idusuario'
	              	      ,'fecha_creacion'
	              	      ,DB::raw('DATE_FORMAT(fecha_cierre,"%Y-%m-%dT%H:%i:%s") as fecha_cierre')
	              	      ,"status"
	              	      ,"total"
	              	      ,"num_productos"
	              	      ,"idmovimiento_general"
	              	      )	             
                  ->where('idcarrito',$idcarrito)                  
                  ->first();
    return $carrito;
}

function valida_fecha_venta_web($fecha){
	// a random date
    $my_date = '2011-09-23';
    $bandera=0; 
	// true if my_date is more than a month ago
	//'yesterday -1 day'
	//date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $fecha) ) ));
	if(strtotime('now')<strtotime ( '-1 day' , strtotime ( $fecha) ) )
		$bandera=1;
	return $bandera;
}

function obten_rol_usuario($idusuario){
	$rol=DB::table('usuarios')
	             ->join('rol','usuarios.idrol','=','rol.idrol')
                  ->where('idusuario',$idusuario)                  
                  ->first();
    return $rol;
}

function define_corte_sesion(){
       if(session('cortecaja')){
         $cortecaja=DB::table('cortecaja')
                  ->where('idcortecaja',session('cortecaja'))
                  ->where('status',0)
                  ->first();
         return $cortecaja;
       }
       else{
       $cortecaja=DB::table('cortecaja')
                  ->where('idusuario',Auth::user()->idusuario)
                  ->where('status',0)
                  ->first();
        if($cortecaja){
            session(['cortecaja' => $cortecaja->idcortecaja]);
            return $cortecaja;
        }
        else{
            date_default_timezone_set("America/Merida");
           $cortecaja2 = Cortecaja::create([
                          'idusuario'      => Auth::user()->idusuario
                        , 'fechaapertura'  => date("Y-m-d H:i:s")
                        , 'status'               => 0
                        
                    ]);
           session(['cortecaja' => $cortecaja2->idcortecaja]);
           return $cortecaja2;
        }
       }
    
}

function obtenerVentasPendientes(){
   $pendientes=\DB::table('movimiento_general')
   ->where('movimiento_general.status',1)
   ->where('movimiento_general.tipo',2)
   ->where('movimiento_general.idcortecaja',session('cortecaja'))
   ->select(\DB::Raw("count(*) as numero_pendientes"))
   ->first();
   if($pendientes){
    return $pendientes->numero_pendientes;
   }
   else{
   	return 0;
   }

   //
}

function tienePermiso($idu,$clave){
	$context["idusuario"] = $idu;
	$context["clave"] = $clave;
	$bO = new BusinessObjectTickets($context);
	$r = $bO->obtenerPermisoXUsuario();
	if(count($r)>0){
		return true;
	}else{
		return false;
	}
}

function obtenerDiferenciaTiempo($idMovGen){
	$context["idmovimiento_general"] = $idMovGen;
	$bo = new BusinessObjectTickets($context);
	$movimientoGeneral = $bo->obtenerMovimientoGeneral();
	if($movimientoGeneral){
		date_default_timezone_set("America/Merida");
		$fechaActual = date("Y-m-d H:i:s");
		$tiempoActual = strtotime($fechaActual);
		$tiempoCreacion = strtotime($movimientoGeneral->fecha);
		$diferencia = $tiempoActual - $tiempoCreacion;
		return config('global.tiempoReserva') - $diferencia;
	}else{
		return 0;
	}
}

function obtenerMovimientoGeneral($idMovGen){
	$context["idmovimiento_general"] = $idMovGen;
	$bo = new BusinessObjectTickets($context);
	$movimientoGeneral = $bo->obtenerMovimientoGeneral();
	if($movimientoGeneral){
		return $movimientoGeneral;
	}else{
		return false;
	}
}

function tieneLugaresGanados($idMovGen){
	$context["idmovimiento_general"] = $idMovGen;
	$bo = new BusinessObjectTickets($context);
	$movimientoGeneral = $bo->obtenerMovimientoGeneral();
	if(count($movimientoGeneral->movimientos)>0){
		return "true";
	}else{
		return "false";
	}
}

/*function obtenerDiferenciaTiempo($fecha){
	date_default_timezone_set("America/Merida");
	$fechaActual = date("Y-m-d H:i:s");
	$tiempoActual = strtotime($fechaActual);
	$tiempoCreacion = strtotime($fecha);
	$diferencia = $tiempoActual - $tiempoCreacion;
	return config('global.tiempoReserva') - $diferencia;
}*/

function formato_fecha($fecha,$corto=0,$diasem=0,$separador='-')
{
	$tmp=explode(' ',$fecha);
	if(count($tmp)>1){
		$fecha=$tmp[0];
	}
	$datos=explode($separador,$fecha);
	$anio=$datos[0];
	$mes=$datos[1];
	$dia=$datos[2];
	switch($mes)
	{
		case '01':
		if($corto==0)
		$mes=' Enero ';
		else
	    $mes='En';
		break;
		case '02':
		if($corto==0)    
		$mes=' Febrero ';
		else
	    $mes='Feb';
		break;
		case '03':
		if($corto==0)
		$mes=' Marzo ';
		else
	    $mes='Mar';
		break;
		case '04':
		if($corto==0)    
		$mes=' Abril ';
		else
		$mes='Abr';
		break;
		case '05':
		if($corto==0)    
		$mes=' Mayo ';
		else
		$mes='May';
		break;
		case '06':
		if($corto==0)    
		$mes=' Junio ';
		else
		$mes='Jun';
		break;
		case '07':
		if($corto==0)    
		$mes=' Julio ';
		else
		$mes='Jul';
		break;
		case '08':
		if($corto==0)    
		$mes=' Agosto ';
		else
		$mes='Ago';
		break;
		case '09':
		if($corto==0)    
		$mes=' Septiembre ';
		else
		$mes='Sept';
		break;
		case '10':
		if($corto==0)    
		$mes=' Octubre ';
		else
		$mes='Oct';
		break;
		case '11':
		if($corto==0)    
		$mes=' Noviembre ';
		else
		$mes='Nov';
		break;
		case '12':
		if($corto==0)    
		$mes=' Diciembre ';
		else
		$mes='Dic';
		break;
	}
	if($diasem!=0){
	   $arraysem=array();
	   if($diasem==1){
    	   $arraysem['Monday']='Lunes';
    	   $arraysem['Tuesday']='Martes';
    	   $arraysem['Wednesday']='Miercoles';
    	   $arraysem['Thursday']='Jueves';
    	   $arraysem['Friday']='Viernes';
    	   $arraysem['Saturday']='Sabado';
    	   $arraysem['Sunday']='Domingo';    
	   }
	   else{
	       $arraysem['Monday']='Lun';
    	   $arraysem['Tuesday']='Mar';
    	   $arraysem['Wednesday']='Mier';
    	   $arraysem['Thursday']='Ju';
    	   $arraysem['Friday']='Vi';
    	   $arraysem['Saturday']='Sab';
    	   $arraysem['Sunday']='Dom';
	   }
	   
	   $dsm=date("l", strtotime($fecha));

	   if(array_key_exists($dsm,$arraysem))
	   	$dayweek=$arraysem[$dsm];
	   else
	   	$dayweek='';	   
	   $cadena_fecha=$dayweek.', '.$dia.' de '.$mes.' de '.$anio;	
	}
	else
	  $cadena_fecha=$dia.' de '.$mes.' de '.$anio;

	return $cadena_fecha;
}

function formato_fecha_cartelera($fecha,$corto=0){
$datos=explode('-',$fecha);
$anio=$datos[0];
$mes=$datos[1];
$dia=$datos[2];
switch($mes)
	{
		case '01':
		if($corto==0)
		$mes='Enero';
		else
	    $mes='En';
		break;
		case '02':
		if($corto==0)    
		$mes='Febrero';
		else
	    $mes='Feb';
		break;
		case '03':
		if($corto==0)
		$mes='Marzo';
		else
	    $mes='Mar';
		break;
		case '04':
		if($corto==0)    
		$mes='Abril';
		else
		$mes='Abr';
		break;
		case '05':
		if($corto==0)    
		$mes='Mayo';
		else
		$mes='May';
		break;
		case '06':
		if($corto==0)    
		$mes='Junio';
		else
		$mes='Jun';
		break;
		case '07':
		if($corto==0)    
		$mes='Julio';
		else
		$mes='Jul';
		break;
		case '08':
		if($corto==0)    
		$mes='Agosto';
		else
		$mes='Ago';
		break;
		case '09':
		if($corto==0)    
		$mes='Septiembre';
		else
		$mes='Sept';
		break;
		case '10':
		if($corto==0)    
		$mes='Octubre';
		else
		$mes='Oct';
		break;
		case '11':
		if($corto==0)    
		$mes='Noviembre';
		else
		$mes='Nov';
		break;
		case '12':
		if($corto==0)    
		$mes='Diciembre';
		else
		$mes='Dic';
		break;
	}
$arraysem['Monday']='Lunes';
$arraysem['Tuesday']='Martes';
$arraysem['Wednesday']='Miércoles';
$arraysem['Thursday']='Jueves';
$arraysem['Friday']='Viernes';
$arraysem['Saturday']='Sábado';
$arraysem['Sunday']='Domingo';
$dsm=date("l", strtotime($fecha));
if(array_key_exists($dsm,$arraysem))
	$dayweek=$arraysem[$dsm];
else
	$dayweek='';

$final=array();
$final['dia']=$dia;
$final['mes']=$mes;
$final['dayweek']=$dayweek;
return $final;
}

