<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Tipo_activo;

class Tipo_activoController extends Controller
{
    public function home(){
    	$tipo_activos=Tipo_activo::all();
    	$datos=array();
    	$datos['tipo_activos']=$tipo_activos;
    	return view('tipo_activo/listado')->with($datos);
    }

    public function formulario(){
    	$tipo_activo=new Tipo_activo;
    	$datos=array();
    	$datos['tipo_activo']=$tipo_activo;
    	$datos['modo']='agregar';
    	return view('tipo_activo/formulario')->with($datos);
    }

    public function formulario_get($id){
    	$tipo_activo=Tipo_activo::find($id);
    	$datos=array();
    	$datos['tipo_activo']=$tipo_activo;
    	$datos['modo']='editar';
    	return view('tipo_activo/formulario')->with($datos);
    }

    public function guardar(Request $r){
    	$info=$r->all();
    	switch ($info['operacion']) {
    		case 'Agregar':
    			$tipo_activo= new Tipo_activo;
    			$tipo_activo->nomtipo_activo=$info['nomtipoact'];
    			$tipo_activo->cve_tipoact=$info['cve_tipoact'];
    			$tipo_activo->save();
    			break;
    		case 'Modificar':
    			$tipo_activo= Tipo_activo::find($info['idtipo_activo']);
    			$tipo_activo->nomtipo_activo=$info['nomtipoact'];
    			$tipo_activo->cve_tipoact=$info['cve_tipoact'];
    			$tipo_activo->save();
    			break;
    		case 'Eliminar':
    			Tipo_activo::where('idtipo_activo',$info['idtipo_activo'])->delete();
    			break;
    	}
    	return redirect()->action('Tipo_activoController@home');
    }
}
