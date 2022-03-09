<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IdentificacionController extends Controller
{
    public function home(){
    	$identificaciones=Identificacion::all();
    	$datos=array();
    	$datos['identificaciones']=$identificaciones;
    	return view('alta/listado')->with($datos);
    }

    public function formulario(){
    	$identificacion=new Identificacion;
    	$datos=array();
    	$datos['identificacion']=$identificacion;
    	$datos['modo']='agregar';
    	return view('alta/formulario')->with($datos);
    }

    public function formulario_get($id){
    	$identificacion=Identificacion::find($id);
    	$datos=array();
    	$datos['identificacion']=$identificacion;
    	$datos['modo']='editar';
    	return view('alta/formulario')->with($datos);
    }

    public function guardar(Request $r){
    	$info=$r->all();
    	switch ($info['operacion']) {
    		case 'Agregar':
                $prueba=DB::table('identificacion')->insertGetId(

                );
    			$identificacion= new Identificacion;
    			$identificacion->clave_interna=$info['clave_interna'];
    			$identificacion->idtipo_identificacion=$info['idtipo_identificacion'];
    			$identificacion->idresguardo=$info['idresguardo'];
    			//$identificacion->idubicacion=$info['idubicacion'];
    			$identificacion->ididentificacion=$info['iddentificacion'];
    			$identificacion->razon_social=$info['razon_social'];
    			$identificacion->idpartida=$info['idpartida'];
    			//$identificacion->idstatus=$info['idstatus'];
    			$identificacion->inventariable=$info['inventariable'];
    			$identificacion->save();
    			break;
    		case 'Modificar':
    			$usuario= Usuario::find($info['idusuario']);
    			$usuario->nomusuario=$info['nomusuario'];
    			$usuario->curp=$info['curp'];
    			$usuario->email=$info['email'];
    			$usuario->telefono=$info['telefono'];
    			$usuario->contacto=$info['contacto'];
    			$usuario->password=$info['password'];
    			$usuario->idrol=$info['idrol'];
    			$usuario->foto=$info['foto'];
    			$usuario->save();
    			break;
    		case 'Eliminar':
    			Usuario::where('ididentificacion',$info['ididentificacion'])->delete();
    			break;
    	}
    	return redirect()->action('IdentificacionController@home');
    }
}
