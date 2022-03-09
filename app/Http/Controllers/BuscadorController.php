<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\Rol;
use App\Model\Area;
use App\Model\Usuario;

class BuscadorController extends Controller
{
    function buscar(Request $r){
    	if ($r->isMethod('post')) {
    		$context=$r->all();

    		$usuario=DB::table('usuario')
    			->select(
    				'idusuario',
    				'nomusuario',
    				'apellidos',
                    'usuario.idrol',
    				'telefono',
    				'gdr_academico',
    				'email',
                    'usuario.idarea',
    				'password',
    				'matricula'
    			)
                ->join('rol','rol.idrol','=','usuario.idrol')
    			->join('area','area.idarea','=','usuario.idarea')
    			->whereRaw("nomusuario like '%".$context['criterio']."%' or apellidos like '%".$context['criterio']."%' or idusuario like '%".$context['criterio']."%'")
    			->get();
    			$criterio=$context['criterio'];
    	}
    	else{
    		$usuario=array();
    		$criterio='';
    	}
    	$datos=array();
        $datos['usuario']=$usuario;
    	$datos['criterio']=$criterio;
    	return view("usuario.listado")->with($datos);
    }

    function activo_resguardo(Request $r){
        if ($r->isMethod('post')) {
            $context=$r->all();

            $usuario=DB::table('usuario')
                ->select(
                    'idusuario',
                    'nomusuario',
                    'apellidos',
                    'usuario.idrol',
                    'telefono',
                    'gdr_academico',
                    'email',
                    'usuario.idarea',
                    'password',
                    'matricula'
                )
                ->join('rol','rol.idrol','=','usuario.idrol')
                ->join('area','area.idarea','=','usuario.idarea')
                ->whereRaw("nomusuario like '%".$context['criterio']."%' or apellidos like '%".$context['criterio']."%' or idusuario like '%".$context['criterio']."%'")
                ->get();
                $criterio=$context['criterio'];
        }
        else{
            $usuario=array();
            $criterio='';
        }
        $datos=array();
        $datos['usuario']=$usuario;
        $datos['criterio']=$criterio;
        $datos['modo']='buscar';
        return view("resguardo.formulario2")->with($datos);
    }
}
