<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Activo;
use App\Model\Tipo_activo;
use App\Model\Partida;
use App\Model\Area;
use App\Model\Identificacion;
use App\Model\Usuario;
use App\Model\Resguardo;
use App\LabelPdf;

class SearchController extends Controller
{
	public function search(Request $r){
		$context=$r->all();
        $criterio=$context['q'];

        $registros=array();

        if (strpos(strtoupper($criterio), 'UTM-') !== false) {
		    //busqueda de activos
		     $registros=Activo::whereRaw("clave_interna like '%".$criterio."%'")
		               ->join('identificacion','identificacion.ididentificacion','=','activo.ididentificacion')
	                    ->select(
	                      	      DB::Raw("CONCAT(clave_interna,' ',nombre,' Activo') as nombre")
	                      	     ,DB::Raw("idactivo as idregistro")
	                      	     ,DB::Raw("'Activo' as tipo")
	                            )
	                   ->get();
		}
		else{
			if(is_numeric($criterio)){
				//busqueda de resguardos
				$registros=Resguardo::whereRaw("no_resguardo like '%".$criterio."%'")
             				->select(
	                      	      DB::Raw("CONCAT(no_resguardo,' Resguardo') as nombre")
	                      	     ,DB::Raw("idresguardo as idregistro")
	                      	     ,DB::Raw("'Resguardo' as tipo")
	                            )
             				->get();
			}
			else{
				//busqueda de funcionarios
		        $usuarios=Usuario::whereRaw("CONCAT(nomusuario,' ',apellidos,' Personal') like '%".$criterio."%'")
		                          ->orWhereRaw("email like '%".$criterio."%'")
		                          ->select(
		                          	      DB::Raw("CONCAT(nomusuario,' ',apellidos,' (',email,')') as nombre")
		                          	     ,DB::Raw("idusuario as idregistro")
		                          	     ,DB::Raw("'Personal' as tipo")
		                          	      )
		                          ->get();
		        //busqueda de funcionarios

		        //busqueda de areas
		        $areas=Area::whereRaw("nomarea like '%".$criterio."%'")
		                    ->select(
		                      	      DB::Raw("CONCAT(nomarea,' Area') as nombre")
		                      	     ,DB::Raw("idarea as idregistro")
		                      	     ,DB::Raw("'Area' as tipo")
		                            )
		                   ->get();
		        //busqueda de areas

		        $registros=$usuarios->merge($areas);
			}
		}

        return response()->json($registros);
	}

	public function search_resguardo(Request $r){
		$context=$r->all();
		$registros=DB::table('resguardo')
		   ->join('usuario','usuario.idusuario','=','resguardo.idusuario')
		   ->select(
		   	        	DB::Raw("CONCAT(nomusuario,' ',apellidos,' (',no_resguardo,')') as nombre")
		   	           ,"no_resguardo  as idregistro"
		   	       )
		   ->whereRaw("CONCAT(nomusuario,' ',apellidos) like '%".$context['q']."%' or no_resguardo like '%".$context['q']."%'")
		   ->get();
		return response()->json($registros);
	}
}