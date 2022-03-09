<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Rol;
use App\Model\Permiso;

class RolController extends Controller
{
    public function home(){
		$rol=Rol::all();
		$datos=array();
		$datos['rol']=$rol;
		return view('rol/listado')->with($datos);
	}

	public function formulario(){
		$rol=new Rol();
		//$roles=new Rol();
		$datos=array();
		$datos['rol']=$rol;
		//$datos['roles']=$roles;
		$datos['modo']='agregar';
		return view('rol/formulario')->with($datos);
	}

	public function formulario_get($id){
		$rol=Rol::find($id);
		$datos=array();
		$datos['rol']=$rol;
		$datos['modo']='editar';
		return view('rol/formulario')->with($datos);
	}

	public function formulario_asignacion($idrol){
		//carga ansiosa
		$rol=Rol::with('permiso')->find($idrol);

		//carga floja

		$permiso=Permiso::all();
		$datos['rol']=$rol;
		$datos['permiso']=$permiso;
		return view('rol/asignacion')->with($datos);
	}

	public function asignar_permisos(Request $r){
		$context=$r->all();
		$rol=Rol::find($context['idrol']);
		if (isset($context['idpermiso'])) {
			$rol->permiso()->sync($context['idpermiso']);
		}
		else{
			$rol->permiso()->detach();
		}
		return redirect()->action('RolController@home');
	}

	public function guardar(Request $r){
		$info=$r->all();
		switch ($info['operacion']) {
			case 'Agregar':
				$rol=new Rol;
				$rol->nomrol=$info['nomrol'];
				$rol->cve_rol=$info['cve_rol'];
				$rol->save();
			break;
			
			case 'Modificar':
				$rol=Rol::find($info['idrol']);
				$rol->nomrol=$info['nomrol'];
				$rol->cve_rol=$info['cve_rol'];
				$rol->save();
			break;

			case 'Eliminar':
				Rol::where('idrol',$info['idrol'])->delete();
			break;
		}
		return redirect()->action('RolController@home');
	}

	public function welcome(){
		return view('welcome');
	}
}
