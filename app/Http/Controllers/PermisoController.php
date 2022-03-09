<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Permiso;


class PermisoController extends Controller
{
    public function home(){
		$permiso=Permiso::all();
		$datos=array();
		$datos['permiso']=$permiso;
		return view('permiso/listado')->with($datos);
	}

	public function formulario(){
		$permiso=new Permiso();
		//$roles=new Rol();
		$datos=array();
		$datos['permiso']=$permiso;
		//$datos['roles']=$roles;
		$datos['modo']='agregar';
		return view('permiso/formulario')->with($datos);
	}

	public function formulario_get($id){
		$permiso=Permiso::find($id);
		$datos=array();
		$datos['permiso']=$permiso;
		$datos['modo']='editar';
		return view('permiso/formulario')->with($datos);
	}

	public function guardar(Request $r){
		$info=$r->all();
		switch ($info['operacion']) {
			case 'Agregar':
				$permiso=new Permiso;
				$permiso->nompermiso=$info['nompermiso'];
				$permiso->cve_permiso=$info['cve_permiso'];
				$permiso->save();
			break;
			
			case 'Modificar':
				$permiso=Permiso::find($info['idpermiso']);
				$permiso->nompermiso=$info['nompermiso'];
				$permiso->cve_permiso=$info['cve_permiso'];
				$permiso->save();
			break;

			case 'Eliminar':
				Permiso::where('idpermiso',$info['idpermiso'])->delete();
			break;
		}
		return redirect()->action('PermisoController@home');
	}

	public function welcome(){
		return view('welcome');
	}
}
