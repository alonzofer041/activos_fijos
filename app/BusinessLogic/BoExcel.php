<?php

namespace App\BusinessLogic;

use App\Model\Activo;
use App\Model\Identificacion;
use App\Model\IndiceInverso;
use App\Model\Resguardo;
use App\Model\Proceso_Carga;
use App\Model\Detalle_Proceso_Carga;
use App\BusinessLogic\BoIndiceInverso;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

ini_set('memory_limit', '-1');
ini_set('max_execution_time',600);

class BoExcel extends BoGeneric{

	var $columnas;

	public function config_columnas(){
		$this->columnas=array();		
		$this->columnas['A']='cant';
		$this->columnas['B']='descripcion';
		$this->columnas['C']='tipo_bien';
		$this->columnas['D']='marca';
		$this->columnas['E']='no_serie';
		$this->columnas['F']='modelo';
		$this->columnas['G']='usuario';
		$this->columnas['H']='ubicacion';
		$this->columnas['I']='no_resguardo';
		$this->columnas['J']='factura';
		$this->columnas['K']='fecha';
		$this->columnas['L']='razon_social';
		$this->columnas['M']='precio';
		$this->columnas['N']='subtotal';
		$this->columnas['O']='iva';
		$this->columnas['P']='total';
		$this->columnas['Q']='partida';
	}

	public function procesa_fk(&$registro){
		// $indice_tipoactivo=IndiceInverso::where('valor',$registro->tipoactivo)
		//                                 ->where('entidad','tipo_activo')
		//                                 ->first();
		// $bandera_consolidado=1;
		// if($indice_tipoactivo){
		// 	$registro->idtipoactivo=$indice_tipoactivo->idregistro;
		// }
		// else{
		// 	$registro->idtipoactivo=0;
		// 	$bandera_consolidado=0;
		// }

	    $indice_ubicacion=IndiceInverso::where('valor',$registro->ubicacion)
		                                ->where('entidad','ubicacion')
		                                ->first();

		$bandera_consolidado=1;

		if($indice_ubicacion){
			if($indice_ubicacion->idregistro!=0)
			 $registro->idubicacion=$indice_ubicacion->idregistro;
			else{
				$registro->idubicacion=0;
				$bandera_consolidado=0;
			}
		}
		else{
			$registro->idubicacion=0;
			$bandera_consolidado=0;
			//dar dalta el indice inverso para la ubicacion
			$inverso_ubicacion=new IndiceInverso();
			$inverso_ubicacion->entidad='ubicacion';
			$inverso_ubicacion->valor=$registro->ubicacion;
			$inverso_ubicacion->save();
		}


		$indice_partida=IndiceInverso::where('valor',$registro->partida)
		                                ->where('entidad','partida')
		                                ->first();

		if($indice_partida){
			$registro->idpartida=$indice_partida->idregistro;
		}
		else{
			$registro->idpartida=0;
			$bandera_consolidado=0;
			//dar dalta el indice inverso para el tipo activo
			$inverso_partida=new IndiceInverso();
			$inverso_partida->entidad='partida';
			$inverso_partida->valor=$registro->partida;
			$inverso_partida->save();
		}


		$indice_tipo_activo=IndiceInverso::where('valor',$registro->tipo_bien)
		                                ->where('entidad','tipo_activo')
		                                ->first();

		if($indice_tipo_activo){
			$registro->idtipoactivo=$indice_tipo_activo->idregistro;
		}
		else{
			$registro->idtipoactivo=0;
			$bandera_consolidado=0;
			//dar dalta el indice inverso para el tipo activo
			$inverso_tipo_activo=new IndiceInverso();
			$inverso_tipo_activo->entidad='tipo_activo';
			$inverso_tipo_activo->valor=$registro->tipo_bien;
			$inverso_tipo_activo->save();
		}

		$indice_usuario=IndiceInverso::where('valor',$registro->usuario)
		                                ->where('entidad','usuario')
		                                ->first();

	    if($indice_usuario){
	    	if($indice_usuario->idregistro!=0){
	    		$registro->idusuario=$indice_usuario->idregistro;	
	    	}
	    	else{
	    		$registro->idusuario=0;
				$bandera_consolidado=0;
	    	}
			
		}
		else{
			$registro->idusuario=0;
			$bandera_consolidado=0;
			//dar dalta el indice inverso para la ubicacion
			$inverso_usuario=new IndiceInverso();
			$inverso_usuario->entidad='usuario';
			$inverso_usuario->valor=$registro->usuario;
			$inverso_usuario->save();
		}

		$registro->consolidado=$bandera_consolidado;
	}

	public function verifica_repetido($registro){
		$resultado=new \StdClass();
		$resultado->bandera=0;
		$resultado->mensaje='';

		//no_resguardo
		// $resg=Resguardo::where('no_resguardo',$registro->no_resguardo)->first();
		// if($resg){
		// 	$resultado->bandera=1;
		// 	$resultado->mensaje.=' El numero de resguardo ya se ha registrado';
		// }

		//no_serie
		$iden1=Identificacion::where('no_serie',$registro->no_serie)->first();
		if($iden1){
			$resultado->bandera=1;
			$resultado->mensaje.=' El numero de serie ya existe';
		}

		// //no_identificacion
		// $iden2=Identificacion::where('no_iden',$registro->no_iden)->first();
		// if($iden2){
		// 	$resultado->bandera=1;
		// 	$resultado->mensaje.=' El numero de identificacion interna ya existe';
		// }

		return $resultado;
	}

	public function procesa_informacion($filas,$idproceso=0){
		if($idproceso==0){
		 $proceso=new Proceso_Carga();
		 $proceso->save();
		}
		else
		 $proceso=Proceso_Carga::find($idproceso);

		$boindice=new BoIndiceInverso();

		
        $contador_insertados=0;
        $contador_por_consolidar=0;
        $contador_duplicados=0;

        $lista_duplicados=array();

        for($i=0;$i<count($filas);$i++){
        	$resultado=$this->verifica_repetido($filas[$i]);
        	$this->procesa_fk($filas[$i]);

        	if($resultado->bandera==0){
        		//no existe duplicidad en los campos por lo que se procede con la insercion en el detalle

        		if($filas[$i]->consolidado==1){
        			//no hay duplicidad y ya esta consolidado por lo que se procede con la insercion del registro
        			//valido que no se haya insertado previamente
        			if(!isset($filas[$i]->status)){
        				$contador_insertados++;
        			    $filas[$i]->status=1;
        			    
        			}        			
        		}
        		else{
        			//no hay duplicidad pero no es posible insertarlo pedir al usuario que consolide
        			$contador_por_consolidar++;
        			$filas[$i]->status=2;
        		}

        		$detalle=new Detalle_Proceso_Carga();
        		$detalle->idproceso_carga=$proceso->idproceso_carga;
				$detalle->cantidad=$filas[$i]->cant;
				$detalle->descripcion=$filas[$i]->descripcion;
				$detalle->no_serie=$filas[$i]->no_serie;
				$detalle->marca=$filas[$i]->marca;
				$detalle->modelo=$filas[$i]->modelo;
				$detalle->usuario=$filas[$i]->usuario;
				$detalle->ubicacion=$filas[$i]->ubicacion;
				$detalle->no_resguardo=$filas[$i]->no_resguardo;
				$detalle->factura=$filas[$i]->factura;
				$detalle->fecha=$filas[$i]->fecha;
				$detalle->razon_social=$filas[$i]->razon_social;
				$detalle->precio=$filas[$i]->precio;
				$detalle->subtotal=$filas[$i]->subtotal;
				$detalle->iva=$filas[$i]->iva;
				$detalle->total=$filas[$i]->total;
				$detalle->idubicacion=$filas[$i]->idubicacion;
				$detalle->idusuario=$filas[$i]->idusuario;
				$detalle->status=$filas[$i]->status;
				$detalle->idactivo=0;
				$detalle->idtipoactivo=$filas[$i]->idtipoactivo;
				$detalle->tipoactivo=$filas[$i]->tipo_bien;
				$detalle->partida=$filas[$i]->partida;
				$detalle->idpartida=$filas[$i]->idpartida;
				$detalle->save();
				if($filas[$i]->status==1){
				 $boindice->consolidar($detalle);	
				}				
        	}
        	else{
        		//Si hay duplicados por lo que se le pedira al usuario atender este tema
        		$contador_duplicados++;
        		$filas[$i]->status=3;
        		$lista_duplicados[]=$filas[$i];
        	}        	
        }

        $proceso->insertados=$contador_insertados;
        $proceso->por_consolidar=$contador_por_consolidar;
        $proceso->duplicados=$contador_duplicados;
        $proceso->num_registros=count($filas);
        $proceso->json=json_encode($lista_duplicados);  
        $proceso->save();
	}

	public function actualiza_proceso($idproceso){
		$proceso=Proceso_Carga::find($idproceso);
		$filas=$proceso->json();
		$this->procesa_informacion($filas,$idproceso);
	}

	public function procesa_archivo($excel){
		$this->config_columnas();		
		$objectReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objectReader->load($excel);
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        //Se obtiene el número máximo de filas
        $contadorFilas=5;
        //Se obtiene el número máximo de columnas
        
        $afil=$objWorksheet->getCell('A'.$contadorFilas)->getFormattedValue();
        $tmp=new \StdClass();
        foreach($this->columnas as $key=>$value){
              // $tmp->$value=$objWorksheet->getCell($key.$contadorFilas)->getFormattedValue();
        	if(($key=='N')||($key=='O')||($key=='P'))
        	 $tmp->$value=$objWorksheet->getCell($key.$contadorFilas)->getOldCalculatedValue();
        	else
        	 $tmp->$value=$objWorksheet->getCell($key.$contadorFilas)->getFormattedValue();

        	// echo $contadorFilas.' '.$key."<br/>";
        }   


        

        $filas=array();        
        while($afil!=''){
            $tmp=new \StdClass();
            foreach($this->columnas as $key=>$value){
		        if(($key=='N')||($key=='O')||($key=='P')){
		         $tmp->$value=$objWorksheet->getCell($key.$contadorFilas)->getOldCalculatedValue();
		         if(($tmp->$value=='N/A')||($tmp->$value==''))
		         	$tmp->$value=0;
		        }
		        else{		         
		         $tmp->$value=$objWorksheet->getCell($key.$contadorFilas)->getFormattedValue();
		         if($key=='M'){
		         	if($tmp->$value=='N/A')
		         		$tmp->$value=0;
		         	else{
		         		$tmp->$value=ltrim($tmp->$value,'$');
		         		$tmp->$value=ltrim($tmp->$value,' ');
		         	}
		         }		         	
		        }
            }            
            $filas[]=$tmp;
            $contadorFilas++;
            $afil=$objWorksheet->getCell('A'.$contadorFilas)->getFormattedValue();
        }

        $this->procesa_informacion($filas);
	}
}