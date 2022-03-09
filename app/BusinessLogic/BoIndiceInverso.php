<?php

namespace App\BusinessLogic;

use App\Model\IndiceInverso;
use App\Model\Usuario;
use App\Model\Ubicacion;
use App\Model\Detalle_Proceso_Carga;
use App\Model\Proceso_Carga;
use App\BusinessLogic\BoActivo;
use App\BusinessLogic\BoResguardo;

class BoIndiceInverso extends BoGeneric{

	public function consolidar(&$registro){
		//Reviso que todas las llaves foraneas esten asociadas
		$bandera=1;
		if($registro->idusuario==0)
			$bandera=0;

		if($registro->idubicacion==0)
			$bandera=0;

		if($registro->idpartida==0)
			$bandera=0;

		if($registro->idtipoactivo==0)
			$bandera=0;
		
		if($bandera==1){
			//cambio el status del registro a 3 para indicar que ya esta consolidado
			$registro->status=3;
			$registro->save();
			//Si ese es el caso entonces inserto el activo
			$objeto=new \StdClass();
			$objeto->identificacion='';		
			$objeto->marca=$registro->marca;
			$objeto->modelo=$registro->modelo;
			$objeto->serie=$registro->no_serie;
			$objeto->clave='';
			$objeto->tipoactivo=$registro->idtipoactivo;		
			$objeto->partida=$registro->idpartida;
			$objeto->inventariable='Inventariable';
			$objeto->nombre=$registro->descripcion;
			$objeto->descripcion=$registro->descripcion;
			$objeto->idubicacion=$registro->idubicacion;
			//facturacion
			$objeto->cantidad=$registro->cantidad;
			$objeto->precio=$registro->precio;
			$objeto->razon=$registro->razon_social;
			$objeto->subtotal=$registro->subtotal;
			$objeto->iva=$registro->iva;
			$objeto->total=$registro->total;
			$objeto->factura=$registro->factura;
			//facturacion

			$boact=new BoActivo();
			$activo=$boact->crear_activo($objeto);

			//proceso de asignacion de resguardo
			if($registro->no_resguardo!=''){
				$bores=new BoResguardo();
				$xxresg=new \StdClass();
				$xxresg->no_resguardo=$registro->no_resguardo;
				$xxresg->idusuario=$registro->idusuario;
				$resguardo=$bores->save($xxresg);

				$bores->agregar_activo($resguardo,$activo);
			}
			//proceso de asignacion de resguardo

			//actualizo los contadores del proceso de carga
			$process=Proceso_Carga::find($registro->idproceso_carga);
			if($process){
				$process->por_consolidar--;
				$process->insertados++;
				$process->save();
			}


		}
		
	}

	public function actualizar($context){
		 //Se edita la entrada en el indice inverso
      IndiceInverso::where('entidad',$context['entity'])
                   ->where('valor',$context['val'])
                   ->update(
                            ["idregistro"=>$context['idregistro']]
                           );

      //Se consolidan los detalles de procesos
      switch($context['entity']){
      	case 'usuario':
	      	// Detalle_Proceso_Carga::where('usuario',$context['val'])
	        //                      ->update(["idusuario"=>$context['idregistro']]);
	        //obtengo todos los detalles del proceso de carga
		    $detalles=Detalle_Proceso_Carga::where('usuario',$context['val'])->get();
		    //para cada detalle recuperado
		    foreach($detalles as $detalle){
		       	 if($detalle->status==2){
		       	 	//Si aun no se consolidado entonces actualizo el registro con la llave correspondiente
		       	 	$detalle->idusuario=$context['idregistro'];
		       	 	$detalle->save();
		       	 	$this->consolidar($detalle);
		      	 }
		    }
      	break;
      	case 'ubicacion':
      	   //obtengo todos los detalles del proceso de carga
           $detalles=Detalle_Proceso_Carga::where('ubicacion',$context['val'])->get();
           //para cada detalle recuperado
           foreach($detalles as $detalle){
           	 if($detalle->status==2){
           	 	//Si aun no se consolidado entonces actualizo el registro con la llave correspondiente
           	 	$detalle->idubicacion=$context['idregistro'];
           	 	$detalle->save();
           	 	$this->consolidar($detalle);
           	 }
           }
      	break;
      	case 'partida':
      	   //obtengo todos los detalles del proceso de carga
           $detalles=Detalle_Proceso_Carga::where('partida',$context['val'])->get();
           //para cada detalle recuperado
           foreach($detalles as $detalle){
           	 if($detalle->status==2){
           	 	//Si aun no se consolidado entonces actualizo el registro con la llave correspondiente
           	 	$detalle->idpartida=$context['idregistro'];
           	 	$detalle->save();
           	 	$this->consolidar($detalle);
           	 }
           }
      	break;
      	case 'tipo_activo':
      	   //obtengo todos los detalles del proceso de carga
           $detalles=Detalle_Proceso_Carga::where('tipoactivo',$context['val'])->get();
           //para cada detalle recuperado
           foreach($detalles as $detalle){
           	 if($detalle->status==2){
           	 	//Si aun no se consolidado entonces actualizo el registro con la llave correspondiente
           	 	$detalle->idtipoactivo=$context['idregistro'];
           	 	$detalle->save();
           	 	$this->consolidar($detalle);
           	 }
           }
      	break;
      }
      

	}

}