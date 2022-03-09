<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Usuario;
use App\Model\Rol;
use App\Model\Area;

class AltaResguardoController extends Controller
{
    public function home(){
		$usuario=new Usuario();
		$usuario=Usuario::all();
		$rol=Rol::all();
		$area=Area::all();
		//$roles=new Rol();
		$datos=array();
		$datos['usuario']=$usuario;
		$datos['rol']=$rol;
		$datos['area']=$area;
		$datos['modo']='alta';
		return view('activo-resguardo/formulario')->with($datos);
	}

	public function formulario(){
		$usuario=new Usuario();
		$rol=Rol::all();
		$area=Area::all();
		//$roles=new Rol();
		$datos=array();
		$datos['usuario']=$usuario;
		$datos['rol']=$rol;
		$datos['area']=$area;
		//$datos['roles']=$roles;
		$datos['modo']='agregar';
		return view('usuario/formulario')->with($datos);
	}

	public function formulario_get($id){
		$usuario=Usuario::find($id);
		$rol=Rol::all();
		$area=Area::all();
		$datos=array();
		$datos['usuario']=$usuario;
		$datos['rol']=$rol;
		$datos['area']=$area;
		$datos['modo']='editar';
		return view('usuario/formulario')->with($datos);
	}

	public function ajax(Request $r){
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

	

	public function guardar(Request $r){
		$info=$r->all();
		switch ($info['operacion']) {
			case 'Agregar':
				$usuario=new Usuario;
				$usuario->nomusuario=$info['nomusuario'];
				$usuario->apellidos=$info['apellidos'];
				$usuario->idrol=$info['idrol'];
				$usuario->telefono=$info['telefono'];
				$usuario->gdr_academico=$info['gdr_academico'];
				$usuario->email=$info['email'];
				$usuario->password=bcrypt($info['password']);
				$usuario->idarea=$info['idarea'];
				$usuario->matricula=$info['matricula'];
				$usuario->save();
			break;
			
			case 'Modificar':
				$usuario=Usuario::find($info['idusuario']);
				$usuario->nomusuario=$info['nomusuario'];
				$usuario->apellidos=$info['apellidos'];
				$usuario->idrol=$info['idrol'];
				$usuario->telefono=$info['telefono'];
				$usuario->gdr_academico=$info['gdr_academico'];
				$usuario->email=$info['email'];
				$usuario->password=bcrypt($info['password']);
				$usuario->idarea=$info['idarea'];
				$usuario->matricula=$info['matricula'];
				$usuario->save();
			break;

			case 'Eliminar':
				Usuario::where('idusuario',$info['idusuario'])->delete();
			break;
		}
		return redirect()->action('AltaResguardoController@home');
	}

	public function welcome(){
		return view('welcome');
	}
}
