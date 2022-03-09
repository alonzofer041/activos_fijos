<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Activo;
use App\Model\Tipo_activo;
use App\Model\Usuario;
use App\Model\Partida;
use App\Model\Identificacion;

class FuncionarioController extends Controller{

	

	function search(Request $r){
		$info=$r->all();		
		$datos=Usuario::select(
			                   "idusuario"
			                  ,"nomusuario"
			                  ,"apellidos"
			                  ,"email"
			                  )
		              ->whereRaw("CONCAT(nomusuario,' ',apellidos) like '%".$info['q']."%' or email like '%".$info['q']."%'")->get();
		// $datos=Usuario::all();
		$final=array();
		$filter='';
		if(isset($info['filter']))
			$filter=$info['filter'];
		foreach($datos as $elemento){
			$tmp=new \StdClass();
			$tmp->idusuario=$filter.$elemento->idusuario;
			$tmp->nombre=$elemento->nomusuario.' '.$elemento->apellidos.' ('.$elemento->email.')';
			$final[]=$tmp;
		}
		return response()
            ->json($final);
	}

}