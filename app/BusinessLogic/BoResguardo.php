<?php

namespace App\BusinessLogic;

use App\Model\Activo;
use App\Model\Resguardo;
use App\Model\Detalle_Resguardo;

class BoResguardo extends BoGeneric{	

	public function agregar_activo($resguardo,$activo){
		$detalle_resguardo=new Detalle_Resguardo();
		$detalle_resguardo->idresguardo=$resguardo->idresguardo;
		$detalle_resguardo->idactivo=$activo->idactivo;
		if(isset($activo->idubicacion))
			$detalle_resguardo->idubicacion=$activo->idubicacion;
		else
			$detalle_resguardo->idubicacion=0;
		$detalle_resguardo->save();

		$activo=Activo::find($activo->idactivo);
		$activo->idresguardo=$resguardo->idresguardo;
		$activo->save();		
	}

	public function editar_activo($resguardo,$activo){

		if(!isset($activo->idactivo))
			$activo->idactivo=$activo->id;

		$detalle_resguardo=Detalle_Resguardo::where('idresguardo',$resguardo->idresguardo)
		                                    ->where('idactivo',$activo->idactivo)
		                                    ->first();

		if(isset($activo->idubicacion))
			$detalle_resguardo->idubicacion=$activo->idubicacion;
		else
			$detalle_resguardo->idubicacion=0;



		$detalle_resguardo->save();
	}

	public function obtener_activos_actuales($resguardo){
		$activo=new Activo();
		return $activo->where('idresguardo',$resguardo->idresguardo)
		      ->get();
	}

	public function quitar_activo($resguardo,$activo,$detalle=0){
		if($detalle==1){
			$detalle=Detalle_Resguardo::where('idresguardo',$resguardo->idresguardo)
			         ->where('idactivo',$activo->idactivo)
			         ->get();
			$detalle->delete();
		}
		$activo=Activo::find($activo->idactivo);
		$activo->idresguardo=0;
		$activo->save();
	}

	public function save($objeto){
		//Encontrar el resguardo a partir del numero
		if($objeto->no_resguardo!=''){
			$oreg=Resguardo::where('no_resguardo',$objeto->no_resguardo)->first();
			if(!$oreg){
				$oreg=new Resguardo();

				if(isset($objeto->fecha))
					$oreg->fecha=$objeto->fecha;
				else
					$oreg->fecha=date('Y-m-d H:i:s');

				if(isset($objeto->idstatus))
					$oreg->idstatus=$objeto->idstatus;
				else
					$oreg->idstatus=1;

				$oreg->idusuario=$objeto->idusuario;
				$oreg->idresponsable=$objeto->idusuario;
				$oreg->no_resguardo=$objeto->no_resguardo;
				$config=dame_configuracion();
				$oreg->json=$config->json_resguardo;
				$oreg->save();
			}

			return $oreg;
		}
		else{
			return null;
		}
		
	}

	
}