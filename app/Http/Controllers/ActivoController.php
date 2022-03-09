<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Activo;
use App\Model\Tipo_activo;
use App\Model\Resguardo;
use App\Model\Partida;
use App\Model\Identificacion;
use App\Model\Proceso_Carga;
use App\Model\IndiceInverso;
use App\Model\Usuario;
use App\Model\Ubicacion;
use App\Model\Area;
use App\Model\Detalle_Proceso_Carga;
use App\LabelPdf;
use App\BusinessLogic\BoExcel;
use App\BusinessLogic\BoIndiceInverso;
use Illuminate\Support\Facades\Auth;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Shared_ZipArchive;

ini_set('memory_limit', '-1');
ini_set('max_execution_time',600);

class ActivoController extends Controller
{

    var $columnas;
    public function columnas(){
        $this->columnas=array();
        $this->columnas['cantidad']='A';
        $this->columnas['descripcion']='B';
        $this->columnas['marca']='C';
        $this->columnas['no_serie']='D';
        $this->columnas['modelo']='E';
        $this->columnas['responsable']='F';
        $this->columnas['ubicacion']='G';
        $this->columnas['no_resguardo']='H';
        $this->columnas['factura']='I';
        $this->columnas['fecha']='J';
        $this->columnas['razon_social']='K';
        $this->columnas['precio_u']='L';
        $this->columnas['subtotal']='M';
        $this->columnas['iva']='N';
        $this->columnas['total']='O';
    }

	public function home(){
    	//$activos=Activo::all();
        $activos=Activo::join('identificacion','identificacion.ididentificacion','=','activo.ididentificacion')
                       ->join('tipo_activo','tipo_activo.idtipo_activo','=','activo.idtipo_activo')
                       ->join('partida','partida.idpartida','=','activo.idpartida')
                       ->leftjoin('resguardo','activo.idresguardo','=','resguardo.idresguardo')
                       ->leftjoin('usuario','usuario.idusuario','=','resguardo.idresponsable')
                       ->leftjoin('area','area.idarea','=','usuario.idarea')
                       ->select(
                               'activo.idactivo'
                              ,'activo.clave_interna'
                              ,'identificacion.nombre'
                              ,'identificacion.marca'
                              ,'identificacion.modelo'
                              ,'identificacion.no_serie'
                              ,'identificacion.descripcion'
                              ,'activo.razon_social'
                              ,'partida.recurso'
                              ,'tipo_activo.nomtipoact'
                              ,DB::Raw("IFNULL(CONCAT(usuario.nomusuario,' ',usuario.apellidos),'Sin personal asignado') as personal")
                              ,DB::Raw("IFNULL(area.nomarea,'Sin area') as nomarea")
                              ,DB::Raw("IFNULL(resguardo.no_resguardo,'Sin resguardo') as no_resguardo")
                              )
                ->get();

           
         
    	$datos=array();

    	$datos['activos']=$activos;
    	return view('activo/listado')->with($datos);
    }

    public function formulario(){
    	$activo=new Activo;
        $tipo_activos=Tipo_activo::all();
        $partidas=Partida::all();
    	$datos=array();
    	$datos['activo']=$activo;
        $datos['tipo_activos']=$tipo_activos;
        $datos['partidas']=$partidas;
    	$datos['modo']='agregar';
    	return view('activo/formulario')->with($datos);
    }

    public function formulario_get($id){
    	$activo=Activo::find($id);
        $tipo_activos=Tipo_activo::all();
        $partidas=Partida::all();
    	$datos=array();
    	$datos['activo']=$activo;
        $datos['tipo_activos']=$tipo_activos;
        $datos['partidas']=$partidas;
    	$datos['modo']='editar';
    	return view('activo/formulario')->with($datos);
    }

    public function search_proveedor(Request $r){
        $info=$r->all();        
        $datos=Activo::select(
                               "razon_social"
                              )
                      ->whereRaw("razon_social like '%".$info['q']."%'")
                      ->groupby("razon_social")
                      ->get();
        // $datos=Usuario::all();
        $final=array();
        $filter='';
        if(isset($info['filter']))
            $filter=$info['filter'];
        foreach($datos as $elemento){
            $tmp=new \StdClass();
            $tmp->nomproveedor=$filter.$elemento->razon_social;
            $final[]=$tmp;
        }
        return response()
            ->json($final);
    }

    public function guardar(Request $r){
    	$info=$r->all();
    	switch ($info['operacion']) {
    		case 'Agregar':
                $identificacion= new Identificacion;
                $identificacion->no_iden=$info['ididentificacion'];
                $identificacion->cantidad=$info['cantidad'];
                $identificacion->marca=$info['marca'];
                $identificacion->modelo=$info['modelo'];
                $identificacion->no_serie=$info['no_serie'];
                $identificacion->precio_u=$info['precio_u'];
                $identificacion->subtotal=$info['subtotal'];
                $identificacion->iva=$info['iva'];
                $identificacion->total=$info['total'];
                $identificacion->nombre=$info['nombre'];
                $identificacion->descripcion=$info['descripcion'];
                $identificacion->save();
                $insertedId = $identificacion->ididentificacion; 
                

    			$activo= new Activo;
    			$activo->clave_interna=$info['clave_interna'];
    			$activo->idtipo_activo=$info['idtipo_activo'];
    			$activo->idresguardo=0;
    			$activo->idubicacion=0;
          $activo->ididentificacion=$insertedId;
    			$activo->razon_social=$info['razon_social'];
    			$activo->idpartida=$info['idpartida'];
    			$activo->idstatus=1;
    			$activo->inventariable=$info['inventariable'];
    			$activo->save();
    			break;
    		case 'Modificar':
                $identificacion= Identificacion::find($info['ididentificacion']);
                $identificacion->no_iden=$info['ididentificacion'];
                $identificacion->cantidad=$info['cantidad'];
                $identificacion->marca=$info['marca'];
                $identificacion->modelo=$info['modelo'];
                $identificacion->no_serie=$info['no_serie'];
                $identificacion->precio_u=$info['precio_u'];
                $identificacion->subtotal=$info['subtotal'];
                $identificacion->iva=$info['iva'];
                $identificacion->total=$info['total'];
                $identificacion->nombre=$info['nombre'];
                $identificacion->descripcion=$info['descripcion'];
                $identificacion->save();

    			$activo= Activo::find($info['idactivo']);
    		    $activo->clave_interna=$info['clave_interna'];
                $activo->idtipo_activo=$info['idtipo_activo'];
                //$activo->idresguardo=$info['idresguardo'];
                //$activo->idubicacion=$info['idubicacion'];
                $activo->ididentificacion=$identificacion->ididentificacion;
                $activo->razon_social=$info['razon_social'];
                $activo->idpartida=$info['idpartida'];
                //$activo->idstatus=$info['idstatus'];
                $activo->inventariable=$info['inventariable'];
    			$activo->save();


    			break;
    		case 'Eliminar':
    			Activo::where('idactivo',$info['idactivo'])->delete();
                Identificacion::where('ididentificacion',$info['ididentificacion'])->delete();
    			break;
    	}
    	return redirect()->action('ActivoController@home');
    }

    public function imprimir_etiquetas(Request $r){
        $context=$r->all();
        $activos=json_decode($context['dts']);
        $pdf = new LabelPdf('5158');
        // $pdf->AddPage();

        $activos_bd=Activo::whereIn('idactivo',$activos)
                         ->join('identificacion','identificacion.ididentificacion','activo.ididentificacion')
                         ->get();

        foreach($activos_bd as $elemento) {           
            $url=action('ActivoController@view_tag',array('id'=>$elemento->idactivo));
            $pdf->Add_Label($url,$elemento->clave_interna,$elemento->no_iden);
        }
        $pdf->Output();        
    }

    public function view_tag(){
        return true;
    }
    
    public function search(Request $r){
        $context=$r->all();
        $criterio=$context['criterio'];
        //si es un numero
        if($esnumero==1){
            //buscar por numero de resguardo
            $activos=Activo::where('idresguardo','=',$criterio)
                    ->get();
        }
        else{
            //Si tiene la cadena UTM-
            if($utm==1){
              $activos=Activo::where('clave_interna','=',$criterio)
                    ->get();
            }
            else{
                //por personal o por area
            }
        }
    }

    public function filter(Request $r){
        $context=$r->all();
        $tipo=$context['type'];
        $idregistro=$context['register'];

        $activos=array();
        switch($tipo){
            case 'Personal':
            $activos=Activo::join('detalle_resguardo','detalle_resguardo.idactivo','=','activo.idactivo')
                       ->join('resguardo','resguardo.idresguardo','=','detalle_resguardo.idresguardo')
                       ->join('identificacion','identificacion.ididentificacion','=','activo.ididentificacion')
                       ->join('tipo_activo','tipo_activo.idtipo_activo','=','activo.idtipo_activo')
                       ->join('partida','partida.idpartida','=','activo.idpartida')
                       ->join('usuario','usuario.idusuario','=','resguardo.idresponsable')
                       ->join('area','area.idarea','=','usuario.idarea')
                       ->select(
                               'activo.idactivo'
                              ,'activo.clave_interna'
                              ,'identificacion.nombre'
                              ,'identificacion.marca'
                              ,'identificacion.modelo'
                              ,'identificacion.no_serie'
                              ,'identificacion.descripcion'
                              ,'activo.razon_social'
                              ,'partida.recurso'
                              ,'tipo_activo.nomtipoact'
                              ,DB::Raw("CONCAT(usuario.nomusuario,' ',usuario.apellidos) as personal")
                              ,'area.nomarea'
                              ,'resguardo.no_resguardo'
                              )
                ->where('usuario.idusuario',$idregistro)
                ->where('resguardo.idstatus',1)
                ->get();
            break;
            case 'Area':
            $activos=Activo::join('detalle_resguardo','detalle_resguardo.idactivo','=','activo.idactivo')
                       ->join('resguardo','resguardo.idresguardo','=','detalle_resguardo.idresguardo')
                       ->join('identificacion','identificacion.ididentificacion','=','activo.ididentificacion')
                       ->join('tipo_activo','tipo_activo.idtipo_activo','=','activo.idtipo_activo')
                       ->join('partida','partida.idpartida','=','activo.idpartida')
                       ->join('usuario','usuario.idusuario','=','resguardo.idresponsable')
                       ->join('area','area.idarea','=','usuario.idarea')
                       ->select(
                               'activo.idactivo'
                              ,'activo.clave_interna'
                              ,'identificacion.nombre'
                              ,'identificacion.marca'
                              ,'identificacion.modelo'
                              ,'identificacion.no_serie'
                              ,'identificacion.descripcion'
                              ,'activo.razon_social'
                              ,'partida.recurso'
                              ,'tipo_activo.nomtipoact'
                              ,DB::Raw("CONCAT(usuario.nomusuario,' ',usuario.apellidos) as personal")
                              ,'area.nomarea'
                              ,'resguardo.no_resguardo'
                              )
                ->where('area.idarea',$idregistro)
                ->where('resguardo.idstatus',1)
                ->get();
            break;
            case 'Activo':
            $activos=Activo::join('identificacion','identificacion.ididentificacion','=','activo.ididentificacion')
                       ->join('tipo_activo','tipo_activo.idtipo_activo','=','activo.idtipo_activo')
                       ->join('partida','partida.idpartida','=','activo.idpartida')
                       ->select(
                               'activo.idactivo'
                              ,'activo.clave_interna'
                              ,'identificacion.nombre'
                              ,'identificacion.marca'
                              ,'identificacion.modelo'
                              ,'identificacion.no_serie'
                              ,'identificacion.descripcion'
                              ,'activo.razon_social'
                              ,'partida.recurso'
                              ,'tipo_activo.nomtipoact'
                              ,'activo.idresguardo'
                              )
                ->where('activo.idactivo',$idregistro)
                ->get();
            for($i=0;$i<count($activos);$i++){
                if($activos[$i]->idresguardo==0){
                    $activos[$i]->personal='Sin personal asignado';
                    $activos[$i]->nomarea='Sin area';
                    $activos[$i]->no_resguardo='Sin resguardo';                    
                }
                else{
                    $resg_data=Resguardo::join('usuario','usuario.idusuario','=','resguardo.idresponsable')
                             ->join('area','area.idarea','=','usuario.idarea')
                             ->select(
                                      DB::Raw("CONCAT(usuario.nomusuario,' ',usuario.apellidos) as personal")
                                      ,'area.nomarea'
                                     ,'resguardo.no_resguardo'
                                     )
                             ->where('resguardo.idresguardo',$activos[$i]->idresguardo)
                             ->first();                             
                    $activos[$i]->personal=$resg_data->personal;
                    $activos[$i]->nomarea=$resg_data->nomarea;
                    $activos[$i]->no_resguardo=$resg_data->no_resguardo;
                }
            }
            break;
            case 'Resguardo':
            $activos=DB::table('activo')                      
                       ->join('detalle_resguardo','detalle_resguardo.idactivo','=','activo.idactivo')
                       ->join('identificacion','identificacion.ididentificacion','=','activo.ididentificacion')
                       ->join('tipo_activo','tipo_activo.idtipo_activo','=','activo.idtipo_activo')
                       ->join('partida','partida.idpartida','=','activo.idpartida')
                       ->join('usuario','usuario.idusuario','=','resguardo.idresponsable')
                       ->join('area','area.idarea','=','usuario.idarea')
                       ->select(
                               'activo.idactivo'
                              ,'activo.clave_interna'
                              ,'identificacion.nombre'
                              ,'identificacion.marca'
                              ,'identificacion.modelo'
                              ,'identificacion.no_serie'
                              ,'identificacion.descripcion'
                              ,'activo.razon_social'
                              ,'partida.recurso'
                              ,'tipo_activo.nomtipoact'
                              ,DB::Raw("CONCAT(usuario.nomusuario,' ',usuario.apellidos) as personal")
                              ,'area.nomarea'
                              ,'resguardo.no_resguardo'
                              )
                    ->where('detalle_resguardo.idresguardo',$idregistro)
                    ->get();
            break;
        }
        
        $datos=array();
        $datos['activos']=$activos;
        return view('activo/listado')->with($datos);

    }

    public function formulario_upload(Request $r){               
        $datos=array();        
        return view('activo/formulario_upload')->with($datos);    
    }

    public function upload(Request $r){
        $this->columnas();
        if($r->hasFile('activo')){
            $archivo=$r->file('activo');
            $objPHPExcel =PHPExcel_IOFactory::load($archivo);
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
            $contadorFilas=5;
            $afil=$objWorksheet->getCell($this->columnas['cantidad'].$contadorFilas)->getFormattedValue();

            $registros=array();
            
            while($afil!=''){
                $tmp=new \StdClass();
                foreach($this->columnas as $key=>$value){
                  $tmp->$key=$objWorksheet->getCell($value.$contadorFilas)->getFormattedValue(); 
                }
                if($tmp->cantidad!=''){
                    $insertGetId=DB::table('activo')
                    ->insertGetId([                                        
                                   "cantidad"=>$tmp->cantidad
                                   ,"descripcion"=>$tmp->descripcion
                                   ,"marca"=>$tmp->marca
                                   ,"no_serie"=>$tmp->no_serie
                                   ,"modelo"=>$tmp->modelo
                                   ,"responsable"=>$tmp->responsable
                                   ,"ubicacion"=>$tmp->ubicacion
                                   ,"no_resguardo"=>$tmp->no_resguardo
                                   ,"factura"=>$tmp->factura
                                   ,"fecha"=>$tmp->fecha
                                   ,"razon_social"=>$tmp->razon_social
                                   ,"precio_u"=>$tmp->precio_u
                                   ,"subtotal"=>$tmp->subtotal
                                   ,"iva"=>$tmp->iva
                                   ,"total"=>$tmp->total
                                ]);
                }
                
                $contadorFilas++;
                $afil=$objWorksheet->getCell($this->columnas['cantidad'].$contadorFilas)->getFormattedValue();
            }

        }
    }

    public function carga_excel(Request $r){
      $boexc=new BoExcel();
      if($r->hasFile('activo')){
            $archivo=$r->file('activo');
            $boexc->procesa_archivo($archivo);
      }
      return $this->carga_home();
      
    }

    public function carga_home(){
      $datos=array();
      $datos['procesos']=Proceso_Carga::select(
                                       \DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as created_at")
                                      ,'idproceso_carga'
                                      ,'insertados'
                                      ,'por_consolidar'
                                      ,'duplicados'
                                      ,'num_registros'
                                      ,'json'
                                      )->get();
      return view('activo.load')->with($datos);
    }

    public function indice_inverso(Request $r){
      $datos=array();
      if($r->isMethod('post')){
        $context=$r->all();
        $indices=IndiceInverso::where('entidad',$context['entidad'])->get();
        $datos['opcion']=$context['entidad'];
        $datos['indices']=$indices;
        switch($context['entidad']){
          case 'usuario':
             $datos['opciones']=Usuario::
                              select(
                                      \DB::Raw("usuario.idusuario as id")
                                    ,\DB::Raw("CONCAT(nomusuario,' ',apellidos) as etiqueta")
                                    )
                              ->get();
          break;
          case 'ubicacion':
            $datos['opciones']=Area::
                                select(
                                        \DB::Raw("area.idarea as id")
                                      ,\DB::Raw("nomarea as etiqueta")
                                      )
                              ->get();
          break;
          case 'partida':
            $datos['opciones']=Partida::
                                select(
                                        \DB::Raw("partida.idpartida as id")
                                      ,\DB::Raw("recurso as etiqueta")
                                      )
                              ->get();
          break;
          case 'tipo_activo':
            $datos['opciones']=Tipo_activo::
                                select(
                                        \DB::Raw("tipo_activo.idtipo_activo as id")
                                      ,\DB::Raw("tipo_activo.nomtipoact as etiqueta")
                                      )
                              ->get();

          break;
        }        
      }
      else{
        $datos['opcion']='';
        $datos['indices']=array();
        $datos['opciones']=array();
      }
      return view('activo.indice_inverso')->with($datos);
    }

    public function save_indice_inverso(Request $r){
      $context=$r->all();
      $boreverse=new BoIndiceInverso();
      $boreverse->actualizar($context);

      $resultado=new \StdClass();
      $resultado->codigo='OK';
      return response()->json($resultado);
      
    }

    public function obtener_detalle_proceso(Request $r){
      $context=$r->all();
      $detalles=Detalle_Proceso_Carga::where('idproceso_carga',$context['idproceso'])->get();
      return response()->json($detalles);
    }
}