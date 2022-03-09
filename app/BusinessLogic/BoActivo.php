<?php

namespace App\BusinessLogic;

use App\Model\Activo;
use App\Model\Identificacion;
use App\Model\Tipo_activo;
use App\Model\Area;

class BoActivo extends BoGeneric{	

	public function crear_activo($objeto){
		$identificacion=new Identificacion();		
		$identificacion->no_iden=$objeto->identificacion;
		$identificacion->cantidad=$objeto->cantidad;
		$identificacion->marca=$objeto->marca;
		$identificacion->modelo=$objeto->modelo;
		$identificacion->no_serie=$objeto->serie;
		$identificacion->precio_u=$objeto->precio;

		if(isset($objeto->subtotal))
			$identificacion->subtotal=$objeto->subtotal;
		else
			$identificacion->subtotal=0;

		if(isset($objeto->iva))
			$identificacion->iva=$objeto->iva;
		else
			$identificacion->iva=0;

		if(isset($objeto->total))
			$identificacion->total=$objeto->total;
		else
			$identificacion->total=0;

		if(isset($objeto->factura))
			$identificacion->factura=$objeto->factura;
		else
			$identificacion->factura='';
		
		$identificacion->nombre=$objeto->nombre;
		$identificacion->descripcion=$objeto->descripcion;
		$identificacion->save();

		$activo=new Activo();

		$activo->clave_interna=$objeto->clave;
		$activo->idtipo_activo=$objeto->tipoactivo;

		if(isset($objeto->idresguardo))
			$activo->idresguardo=$objeto->idresguardo;
		else
			$activo->idresguardo=0;

		if(isset($objeto->idstatus))
			$activo->idstatus=$objeto->idstatus;
		else
			$activo->idstatus=0;

		$activo->ididentificacion=$identificacion->ididentificacion;
		$activo->razon_social=$objeto->razon;
		$activo->idpartida=$objeto->partida;
		$activo->inventariable=$objeto->inventariable;
		$activo->idubicacion=$objeto->idubicacion;
		$activo->save();



		//registrar la clave_interna que funge como numero de inventario
		$bandera_asignar_numero=1;
		if(isset($objeto->clave)){
			if($objeto->clave!='')
				$bandera_asignar_numero=0;
		}
		$prefijo_area='';
		$prefijo_tipoactivo='';
		$prefijo_activo='';
		$final_clave='';

		$tipoact=Tipo_activo::find($objeto->tipoactivo);
		if($tipoact){
			$prefijo_tipoactivo=$tipoact->clave_tipo;
		}

		$area=Area::find($objeto->idubicacion);
		if($area){
			$prefijo_area=$area->codigo;
		}
		



		$final_clave=$prefijo_area.'-'.$prefijo_tipoactivo.'-'.$activo->idactivo;
		Activo::where('idactivo',$activo->idactivo)
		      ->update(
		      	      [
		      	      	"clave_interna"=>$final_clave
		      	      ]
		      	      );
		//registrar la clave_interna que funge como numero de inventario


		return $activo;
	}

	public function editar_activo($objeto){
		$activo=Activo::find($objeto->id);
		
		$identificacion=Identificacion::find($activo->ididentificacion);	
		$identificacion->no_iden=$objeto->identificacion;
		$identificacion->cantidad=$objeto->cantidad;
		$identificacion->marca=$objeto->marca;
		$identificacion->modelo=$objeto->modelo;
		$identificacion->no_serie=$objeto->serie;
		$identificacion->precio_u=$objeto->precio;
		$identificacion->subtotal=0;
		$identificacion->iva=0;
		$identificacion->total=0;
		$identificacion->nombre=$objeto->nombre;
		$identificacion->descripcion=$objeto->descripcion;
		$identificacion->save();

		
		$activo->clave_interna=$objeto->clave;
		$activo->idtipo_activo=$objeto->tipoactivo;		
		$activo->ididentificacion=$identificacion->ididentificacion;
		$activo->razon_social=$objeto->razon;
		$activo->idpartida=$objeto->partida;
		$activo->inventariable=$objeto->inventariable;
		$activo->save();
		return $activo;
	}

	public function listar_activos_sin_resguardo(){
		return Activo::where('idresguardo',0)
		       ->join('identificacion','identificacion.ididentificacion','=','activo.ididentificacion')
		       ->get();
	}

	public function listar_activos($activos){
		
		return Activo::select(
			         'clave_interna'
			        ,'razon_social'
			        ,'inventariable'
			        ,'idstatus'
			        ,'nomtipoact'
			        ,'tipo_activo.idtipo_activo'
			        ,'tipo_activo.nomtipoact'
			        ,'partida.idpartida'
			        ,'activo.idactivo'
			        ,'recurso'
			        ,'no_iden'
			        ,'cantidad'
			        ,'marca'
			        ,'modelo'
			        ,'no_serie'
			        ,'precio_u'
			        ,'subtotal'
			        ,'iva'
			        ,'total'
			        ,'nombre'
			        ,'descripcion'
			          )
		        ->join('partida','partida.idpartida','=','activo.idpartida')
		        ->join('tipo_activo','tipo_activo.idtipo_activo','=','activo.idtipo_activo')
		        ->join('identificacion','identificacion.ididentificacion','=','activo.ididentificacion')
		        ->whereIn('activo.idactivo',$activos)
		        ->get();

	}

	
}