<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Activo;
use App\Model\Tipo_activo;
use App\Model\Usuario;
use App\Model\Area;
use App\Model\Partida;
use App\Model\Resguardo;
use App\Model\Identificacion;
use App\Model\Detalle_Resguardo;
use App\Model\Ubicacion;
use App\BusinessLogic\BoActivo;
use App\BusinessLogic\BoResguardo;
use App\PdfResguardo;
use Illuminate\Support\Facades\Auth;

class ResguardoController extends Controller{

	function home(){
		$datos=array();
		$datos['resguardos']=DB::table('resguardo')->join('usuario','usuario.idusuario','=','resguardo.idresponsable')->orderby('fecha','desc')->get();
		$boact=new BoActivo();
		$datos['activos']=$boact->listar_activos_sin_resguardo();	

		return view('resguardo/index')->with($datos);
	}

	function formulario(Request $r){
		$datos=array();
		$datos['partidas']=Partida::all();
		$datos['tipos']=Tipo_activo::all();
		$datos['areas']=Area::all();
		$datos['ubicaciones']=Ubicacion::orderby('descripcion')->get();
		$datos['operacion']='Agregar';
		$datos['activos']=array();
		if ($r->isMethod('post')) {
			$context=$r->all();
		    if(isset($context['dts_activos'])){
		    	$tmp_activos=json_decode($context['dts_activos']);
		    	$boact=new BoActivo();
		    	$datos['activos']=$boact->listar_activos($tmp_activos);
		    	$datos['operacion']='Agregar';
		    }
		}
		return view('resguardo/formulario')->with($datos);
	}

	function formulario2(Request $r){
		$context=$r->all();
		$obj=new \StdClass();
		$obj->idresguardo=$context['cvdfs'];

		$resguardo=Resguardo::find($context['cvdfs']);
		$usuario=Usuario::find($resguardo->idresponsable);

		$resguardo->nomusuario=$usuario->nomusuario.' '.$usuario->apellidos;

		$detalles=Detalle_Resguardo::where('idresguardo',$context['cvdfs'])
		                           ->get();
		$lst_activos=array();
		$map_ubicaciones=array();
		foreach($detalles as $det){
			$lst_activos[]=$det->idactivo;
			$map_ubicaciones[$det->idactivo]=$det->idubicacion;
		}

		$boact=new BoActivo();
		
		$datos=array();
		$datos['partidas']=Partida::all();
		$datos['tipos']=Tipo_activo::all();
		$datos['areas']=Area::all();
		$datos['ubicaciones']=Ubicacion::orderby('descripcion')->get();
		$datos['operacion']='Editar';
		$datos['resguardo']=$resguardo;

		$tgbcfd=$boact->listar_activos($lst_activos);
		for($i=0;$i<count($tgbcfd);$i++){
			$tgbcfd[$i]->idubicacion=$map_ubicaciones[$tgbcfd[$i]->idactivo];
		}		

		$datos['activos']=$tgbcfd;		
		return view('resguardo/formulario')->with($datos);
	}

	function search_funcionario(Request $r){
		$info=$r->all();		
		// $datos=Usuario::whereRaw("CONCAT(nomusuario,' ',apellidos) like '%".$info['q']."%'")->get();
		$datos=Usuario::all();
		$final=array();
		foreach($datos as $elemento){
			$tmp=new \StdClass();
			$tmp->idusuario=$elemento->idusuario;
			$tmp->nombre=$elemento->nomusuario.' '.$elemento->apellidos;
			$final[]=$tmp;
		}
		return response()
            ->json($final);
	}

	public function save(Request $r){
		$context=$r->all();			
		$operacion=$context['operacion'];			
		switch($operacion){
			case 'Agregar':
				if($context['idresponsable']==-1){
					//es necesario crear el responsable
					$usuario=new Usuario();
					$usuario->nomusuario=$context['new-nombre'];
					$usuario->apellidos=$context['new-apellido'];
					$usuario->idrol=1;
					$usuario->telefono=$context['new-telefono'];
					$usuario->gdr_academico='';
					$usuario->email=$context['new-email'];
					$usuario->password=bcrypt('123456');
					$usuario->idarea=$context['new-area'];
					$usuario->matricula='';
					$usuario->save();
				}
				else{
				    //no es necesario crear el responsable
				    $usuario=Usuario::find($context['idresponsable']);
				}

				$resguardo=new Resguardo();
				$resguardo->idusuario=0;
				$resguardo->no_resguardo=$context['num_resguardo'];
				$resguardo->fecha=date('Y-m-d');
				$resguardo->idstatus=1;
				$resguardo->idresponsable=$usuario->idusuario;
				$resguardo->save();

				$datos=json_decode($context['dts']);
				$boactivo=new BoActivo();
				$boresguardo=new BoResguardo();


				$activos_bd=$boresguardo->obtener_activos_actuales($resguardo);


				//tratamiento de activos
				$por_insertar=array();
				$por_modificar=array();
				$por_eliminar=array();



				foreach($datos as $activo){
					if (strpos($activo->id, 'new_') !== false) {
						$por_insertar[]=$activo;
					}
					else{
						$bandera=0;
						foreach($activos_bd as $el_bd){
							if($el_bd->idactivo==$activo->id){
								$bandera=1;
							}
						}
						if($bandera==0){
							$por_modificar[]=$activo;
						}
					}
				}

				foreach($activos_bd as $el_bd){
					$bandera=0;
					foreach($datos as $activo){
						if($el_bd->idactivo==$activo->id){
							$bandera=1;
						}
					}
					if($bandera==0){
						$por_eliminar[]=$el_bd;
					}
				}
				//tratamiento de activos

				

				foreach($por_insertar as $activo){
					$o_activo=$boactivo->crear_activo($activo);
					$boresguardo->agregar_activo($resguardo,$o_activo);	
				}

				foreach($por_modificar as $activo){
					$o_activo=Activo::find($activo->id);
					$boactivo->editar_activo($activo);
					$boresguardo->agregar_activo($resguardo,$o_activo);	
				}

				foreach($por_eliminar as $activo){
					$boresguardo->quitar_activo($resguardo,$o_activo);	
				}

			break;
			case 'Editar':			
			    if($context['idresponsable']==-1){
					//es necesario crear el responsable
					$usuario=new Usuario();
					$usuario->nomusuario=$context['new-nombre'];
					$usuario->apellidos=$context['new-apellido'];
					$usuario->idrol=1;
					$usuario->telefono=$context['new-telefono'];
					$usuario->gdr_academico='';
					$usuario->email=$context['new-email'];
					$usuario->password=bcrypt('123456');
					$usuario->idarea=$context['new-area'];
					$usuario->matricula='';
					$usuario->save();
				}
				else{
				    //no es necesario crear el responsable
				    $usuario=Usuario::find($context['idresponsable']);
				}

			    $resguardo=Resguardo::find($context['idresguardo']);			    
			    $resguardo->idusuario=Auth::user()->idusuario;
			    $resguardo->no_resguardo=$context['num_resguardo'];			  
			    $resguardo->idstatus=1;
			    $resguardo->idresponsable=$usuario->idusuario;
			    $resguardo->save();

			    $datos=json_decode($context['dts']);
			    $boactivo=new BoActivo();
			    $boresguardo=new BoResguardo();

			    $activos_bd=$boresguardo->obtener_activos_actuales($resguardo);


			    //tratamiento de activos
			    $por_insertar=array();
			    $por_modificar=array();
			    $por_eliminar=array();

			    foreach($datos as $activo){
					if (strpos($activo->id, 'new_') !== false) {
							$por_insertar[]=$activo;
					}
					else{
							$bandera=0;
							foreach($activos_bd as $el_bd){
								if($el_bd->idactivo==$activo->id){
									$bandera=1;
								}
							}
							if($bandera==1){
								$por_modificar[]=$activo;
							}
						}
			    }

			    foreach($activos_bd as $el_bd){
					$bandera=0;
					foreach($datos as $activo){
						if($el_bd->idactivo==$activo->id){
							$bandera=1;
						}
					}
					if($bandera==0){
						$por_eliminar[]=$el_bd;
					}
			    } 
			    //tratamiento de activos			

				foreach($por_insertar as $activo){
					$o_activo=$boactivo->crear_activo($activo);
					$boresguardo->agregar_activo($resguardo,$o_activo);	
				}



				foreach($por_modificar as $activo){
					$o_activo=Activo::find($activo->id);
					$boactivo->editar_activo($activo);
					$boresguardo->editar_activo($resguardo,$activo);	
				}

				foreach($por_eliminar as $activo){
					$boresguardo->quitar_activo($resguardo,$o_activo);	
				}
			 break;
		}

		return $this->home();
	}

	public function print(Request $r){	
		$context=$r->all();				
		$pdf=new PdfResguardo();
		$resguardo=\DB::table('resguardo')->where('idresguardo',$context['cvdfs'])->first();
		$responsable=\DB::table('usuario')->where('idusuario',$resguardo->idusuario)->first();
		$pdf->activos=\DB::table('detalle_resguardo')		           
		           ->join('activo','activo.idactivo','=','detalle_resguardo.idactivo')
		           ->join('identificacion','activo.ididentificacion','=','identificacion.ididentificacion')
		           ->where('detalle_resguardo.idresguardo',$context['cvdfs'])
		           ->get();
		$pdf->config=json_decode($resguardo->json);
		$pdf->folio=$resguardo->idresguardo;
		$pdf->logo_utm=asset('public/images/logo_utm.png');
		$tmp=explode(' ',$resguardo->fecha);
		$pdf->responsable=$responsable->nomusuario.' '.$responsable->apellidos;
		$pdf->fecha=formato_fecha($tmp[0]);
		$pdf->make();

	}

	public function filter(Request $r){
		$context=$r->all();	
		$resguardos=Resguardo::where('no_resguardo',$context['idrssg'])->get();		
		$datos=array();
		$datos['resguardos']=$resguardos;
		$boact=new BoActivo();
		$datos['activos']=$boact->listar_activos_sin_resguardo();
		return view('resguardo/index')->with($datos);
	}

}