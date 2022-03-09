<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Area;

class AreaController extends Controller
{
    public function home(){
		$area=Area::all();
		$datos=array();
		$datos['area']=$area;
		return view('area/listado')->with($datos);
	}

	public function formulario(){
		$area=new Area();
		$datos=array();
		$datos['area']=$area;
		$datos['modo']='agregar';
		return view('area/formulario')->with($datos);
	}

	public function formulario_get($id){
		$area=Area::find($id);
		$datos=array();
		$datos['area']=$area;
		$datos['modo']='editar';
		return view('area/formulario')->with($datos);
	}


	public function guardar(Request $r){
		$info=$r->all();
		switch ($info['operacion']) {
			case 'Agregar':
				$area=new Area;
				$area->nomarea=$info['nomarea'];
				$area->cve_area=$info['cve_area'];
				$area->save();
			break;
			
			case 'Modificar':
				$area=Area::find($info['idarea']);
				$area->nomarea=$info['nomarea'];
				$area->cve_area=$info['cve_area'];
				$area->save();
			break;

			case 'Eliminar':
				Area::where('idarea',$info['idarea'])->delete();
			break;
		}
		return redirect()->action('AreaController@home');
	}

	public function welcome(){
		return view('welcome');
	}
}
