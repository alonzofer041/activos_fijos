<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Model\Alumno;
use App\Model\Usuario;
use App\XmlSvg;


class DemoController extends Controller{

	public function home(){
		$alumnos=Alumno::
		         join('grupoxalumno','grupoxalumno.idalumno','alumno.idalumno')
		         ->join('usuario','usuario.idusuario','alumno.idusuario')
		         ->where('idgrupo',630)
		         ->get();
        
        $datos=array();
        $datos['alumnos']=$alumnos;
		return view('administrador.home')->with($datos);
    

  }

  public function test(){
  	return view('test');
  }

  public function pass_reset(){
  	DB::table('usuario')->update(["password"=>bcrypt('123456')]);
  }

  function append_simplexml(&$simplexml_to, &$simplexml_from)
  {
      foreach ($simplexml_from as $simplexml_child)
      {
          $simplexml_temp = $simplexml_to->addChild($simplexml_child->getName(), (string) $simplexml_child);
          foreach ($simplexml_child->attributes() as $attr_key => $attr_value)
          {
              $simplexml_temp->addAttribute($attr_key, $attr_value);
          }

          $this->append_simplexml($simplexml_temp, $simplexml_child);
      }
  } 

  public function transform_style(&$elemento){
    // echo (string)$elemento['style'];
    $hijos=$elemento->children();
    for($j=0;$j<count($hijos);$j++){
      if(isset($hijos[$j]['style'])){
        $fill='none';
        $stroke='none';
        $st=(string)($hijos[$j]['style']);
        $tmp=explode(';',$st);
        for($k=0;$k<count($tmp);$k++){
          $tmp2=explode(':',$tmp[$k]);
          if($tmp2[0]=='fill'){
            $fill=$tmp2[1];
          }
          else{
            if($tmp2[0]=='stroke'){
              $stroke=$tmp2[1];
            }
          }
        }
        // echo $fill.'<br/>';
        // echo $stroke.'<br/>';
         unset($hijos[$j]['style']);
         $hijos[$j]->addAttribute('fill',$fill);
         $hijos[$j]->addAttribute('stroke',$stroke);
      }

    }
    
  }

  public function elimina_style(&$elemento){
    // echo (string)$elemento['style'];
    unset($elemento['style']);
    $hijos=$elemento->children();
    if(count($hijos)!=0){
      for($j=0;$j<count($hijos);$j++){
       $this->elimina_style($hijos[$j]);
      }
    }    
  }

  public function mapa2(){
    $edificios=array();
    $svg_utm=new XmlSvg();
    $mapa=asset('public/mapa_utm.svg');
    $svg_utm->init($mapa);
    $ubicaciones=$svg_utm->dame_ubicaciones2();
    for($i=0;$i<count($ubicaciones);$i++){      
      $ubicaciones[$i]->addAttribute('class','ubicacion');
      $csdwe=explode('-',$ubicaciones[$i]['id']);
      $edificio=$csdwe[0];
      if(!array_key_exists($edificio,$edificios)){
        $edificios[$edificio]=array();
      }

      $edificios[$edificio][]=$ubicaciones[$i];
    }
    $plantilla='<g id="ubicaciones">';
    foreach($edificios as $llave=>$datos){
      $plantilla.='<g id="edificio_'.$llave.'">';
      foreach($datos as $elemento){
         $plantilla.=$elemento->asXML();
      }
      $plantilla.='</g>';      
    }
    $plantilla.='</g>';

    $fp = fopen('utm_edificios.svg', 'w');
    fwrite($fp,$plantilla);
    fclose($fp);

  }

  public function mapa(){    
    $grupos=array();
    $malos=array();

    // manejo del mapa de carlos
    // $svg_carlos=new XmlSvg();
    // $mapa=asset('public/utm_carlos.svg');
    // $svg_carlos->init($mapa);
    // $ubicaciones=$svg_carlos->dame_ubicaciones(1);
    // foreach($ubicaciones->children() as $elemento){
    //  if($elemento->getName()=='g'){
    //   if(isset($elemento['class']) && isset($elemento['id'])){
    //     $grupos[]=$elemento;
    //   }      
    //  }
    // }    
    // manejo del mapa de carlos

    //manejo del mapa de pedro
    $svg_pedro=new XmlSvg();
    $mapa=asset('public/utm_pedro.svg');
    $svg_pedro->init($mapa);
    $ubicaciones=$svg_pedro->dame_ubicaciones(0);
    foreach($ubicaciones->children() as $elemento){
     $bandera_buenos=0;
     if($elemento->getName()=='g'){
      if(isset($elemento['class']) && isset($elemento['id'])){
        $bandera_buenos=1;        
      }      
     }

     if($bandera_buenos==1){      
      $grupos[]=$elemento;
    }      
     else{
      $malos[]=$elemento;
    }      
    }

    for($i=0;$i<count($malos);$i++){
      $this->elimina_style($malos[$i]);      
    }

    $plantilla='<?xml version="1.0" encoding="UTF-8" standalone="no"?><svg 
   viewBox="1414.407 2090.404 6681.002 4794.596"
   width="6681.002pt"
   height="4794.596pt">';

   for($i=0;$i<count($malos);$i++){
    $plantilla.=$malos[$i]->asXML();
   }

   $plantilla.='</svg>';

    // manejo del mapa de pedro
    // dd($grupos);
    // $svg_plantilla=new XmlSvg();
    // // $plantilla=asset('public/utm_pedro_2.svg');
    // $svg_plantilla->init($plantilla,1);
    // $this->append_simplexml($svg_plantilla->obj_xml,$malos);

$fp = fopen('malos'.date('YmdHis').'.svg', 'w');
    fwrite($fp,$plantilla);
    fclose($fp);
  }

  public function visor_mapa(){
    return view('visor_mapa/index');
  }

  function parsear_path($cadena_path){
  
  //    M3613.845,3342.305h-258.146v119.896l165.648-1.86c15.434,28.226,83.963,23.378,92.496,1.86    C3614.25,3461.447,3614.25,3421.482,3613.845,3342.305z

  $token='M';
  $comandos[]=array();
  $comandos[]='M';
  $comandos[]='L';
  $comandos[]='H';
  $comandos[]='V';
  $comandos[]='C';
  $comandos[]='S';
  $comandos[]='Q';
  $comandos[]='T';
  $comandos[]='A';
  $comandos[]='Z';
  $comandos[]='m';
  $comandos[]='l';
  $comandos[]='h';
  $comandos[]='v';
  $comandos[]='c';
  $comandos[]='s';
  $comandos[]='q';
  $comandos[]='t';
  $comandos[]='a';
  $comandos[]='z';

  $resultados=array();
  $resultados['secuencia']=array();
  // $resultados['M']=array("indice"=>-1,"cadena"=>'');
  // $resultados['L']=array("indice"=>-1,"cadena"=>'');
  // $resultados['H']=array("indice"=>-1,"cadena"=>'');
  // $resultados['V']=array("indice"=>-1,"cadena"=>'');
  // $resultados['C']=array("indice"=>-1,"cadena"=>'');
  // $resultados['S']=array("indice"=>-1,"cadena"=>'');
  // $resultados['Q']=array("indice"=>-1,"cadena"=>'');
  // $resultados['T']=array("indice"=>-1,"cadena"=>'');
  // $resultados['A']=array("indice"=>-1,"cadena"=>'');
  // $resultados['Z']=array("indice"=>-1,"cadena"=>'');
  // $resultados['m']=array("indice"=>-1,"cadena"=>'');
  // $resultados['l']=array("indice"=>-1,"cadena"=>'');
  // $resultados['h']=array("indice"=>-1,"cadena"=>'');
  // $resultados['v']=array("indice"=>-1,"cadena"=>'');
  // $resultados['c']=array("indice"=>-1,"cadena"=>'');
  // $resultados['s']=array("indice"=>-1,"cadena"=>'');
  // $resultados['q']=array("indice"=>-1,"cadena"=>'');
  // $resultados['t']=array("indice"=>-1,"cadena"=>'');
  // $resultados['a']=array("indice"=>-1,"cadena"=>'');
  // $resultados['z']=array("indice"=>-1,"cadena"=>'');

  //limpiar la cadena

  $cadena_path=trim($cadena_path);
  $cadena_path=str_replace(" ", "",$cadena_path);

  $caracteres=strlen($cadena_path);
  $token_m_find=0;
  $token_other_find=0;
  $i=0;
  $resultado='';
  $token_actual='';
  $indice_insertado=-1;

  while($i<$caracteres){
     if(in_array($cadena_path[$i],$comandos)){       
       $token_actual=$cadena_path[$i];
       $cadena_act='';
       // $resultados[$token_actual]['indice']=$i;
       $resultados['secuencia'][]=array("comando"=>$token_actual,"contenido"=>'');
       $indice_insertado++;
     }
     else{
       $resultados['secuencia'][$indice_insertado]['contenido'].=$cadena_path[$i];
       // $resultados[$token_actual]['cadena'].=$cadena_path[$i];
     }     
     $i++;
  }
  return $resultados;
}

public function analisis_geometrico(){
    $svg=new XmlSvg();
    $mapa=asset('public/mapa_utm_prepross.svg');
    $svg->init($mapa);
    $targets=$svg->obj_xml->g[1];
    $svg_procesado_rect='';
    $dimension1=count($targets);
    for($i=0;$i<count($dimension1);$i++){
      //todos los grupos del edificio
      $edificios=$targets[$i]->children();
      $num_edificios=count($edificios);
      for($j=0;$j<$num_edificios;$j++){
         $ubicaciones=$edificios[$j]->children();
         $dom_papa=dom_import_simplexml($edificios[$j]);
         $num_ubicaciones=count($ubicaciones);
         for($k=0;$k<$num_ubicaciones;$k++){
            $modo='';
            $texto=null;
            $geometricos=$ubicaciones[$k]->children();
            $num_geometricos=count($geometricos);
            $bandera_transform=0;
            for($l=0;$l<$num_geometricos;$l++){
              switch($geometricos[$l]->getName()){
                  case 'rect':
                  if($modo==''){
                      if(in_array($edificios[$j]["id"],array("edificio_T"))){
                        $bandera_transform=1;                 
                        $translate=$ubicaciones[$k]["transform"];
                        $translate=str_replace("translate(", "",$translate);
                        $translate=str_replace(")", "",$translate);
                        $translate=trim($translate);
                        $translate=(float)$translate;

                        $temp_x=(float)$geometricos[$l]["x"];
                        $temp_y=(float)$geometricos[$l]["y"];

                        // $matrix=$geometricos[$l]["transform"];
                        // $matrix=str_replace("matrix(", "",$matrix);
                        // $matrix=str_replace(")", "",$matrix);
                        // $matrix=trim($matrix);

                        // $params=array();
                        // $tmp=explode(' ',$matrix);
                        // foreach($tmp as $el){
                        //   if($el!='')
                        //     $params[]=$el;
                        // }

                        $ancho_caja=(float)$geometricos[$l]["width"];
                        $alto_caja=(float)$geometricos[$l]["height"];

                        //al valor de la coordenada x sumarle el translate
                        $base_x=($temp_x)+$translate;
                        $base_y=($temp_y);
                        
                        
                        //a los textos restarle el x e y del tempx e tempy y agregarselos a la matriz
                        //la pendiente es la tangente y se calcula diviendo el seno entre el coseno
                        if(isset($ubicaciones[$k]['data-p']))
                          $ubicaciones[$k]['data-p']=round(($alto_caja/$ancho_caja),2);
                        else
                          $ubicaciones[$k]->addAttribute('data-p',round(($alto_caja/$ancho_caja),2));

                        //quitar el translate del g
                        unset($ubicaciones[$k]["transform"]);                        

                        //Poner el x e y del rect en 0
                        $geometricos[$l]["x"]=0;
                        $geometricos[$l]["y"]=0;

                        

                        
                        
                        // 
                      }
                  }
                  
                  break;
              }
            }
            if($bandera_transform==1){

              for($i=0;$i<count($ubicaciones[$k]->text);$i++){
                    $srp1=$ubicaciones[$k]->text[$i]["transform"][0];
                    $srp1=str_replace("matrix(", "",$srp1);
                    $srp1=str_replace(")", "",$srp1);
                    $srp1=trim($srp1);
                    $params=array();
                    $el_tmp=explode(' ',$srp1);
                    foreach($el_tmp as $xl){
                      if($xl!='')
                       $params[]=$xl;
                    }

                    $nwx=$params[4]-($base_x);
                    $nwx=$params[5]-($base_y);

                    $ubicaciones[$k]->text[$i]["transform"]="matrix(1 0 0 1 ".$nwx." ".$nwx.")";

                  }
              

              $ss='<svg x="'.$base_x.'" y="'.$base_y.'" width="'.abs($ancho_caja).'" height="'.abs($alto_caja).'" style="overflow:visible">';
            
              $ss.=$ubicaciones[$k]->asXmL();
              $ss.='</svg>';

              $svg_procesado_rect.=$ss;             
            }
         }
      }
    }

    $fp = fopen('rect_pros5'.date('YmdHis').'.svg', 'w');
    fwrite($fp,$svg_procesado_rect);
    fclose($fp);
  }

  public function analisis_geometrico_S2(){
    $svg=new XmlSvg();
    $mapa=asset('public/mapa_utm_prepross.svg');
    $svg->init($mapa);
    $targets=$svg->obj_xml->g[1];
    $svg_procesado_rect='';
    $dimension1=count($targets);
    for($i=0;$i<count($dimension1);$i++){
      //todos los grupos del edificio
      $edificios=$targets[$i]->children();
      $num_edificios=count($edificios);
      for($j=0;$j<$num_edificios;$j++){
         $ubicaciones=$edificios[$j]->children();
         $dom_papa=dom_import_simplexml($edificios[$j]);
         $num_ubicaciones=count($ubicaciones);
         for($k=0;$k<$num_ubicaciones;$k++){            
              $modo='';
              $texto=null;
              $geometricos=$ubicaciones[$k]->children();
              $num_geometricos=count($geometricos);
              $bandera_transform=0;
              for($l=0;$l<$num_geometricos;$l++){
                switch($geometricos[$l]->getName()){
                    case 'rect':
                    if($modo==''){                      
                        
                        if(in_array($edificios[$j]["id"],array("edificio_T"))){
                          $bandera_transform=1;                 
                          $translate=$ubicaciones[$k]["transform"];
                          $translate=str_replace("translate(", "",$translate);
                          $translate=str_replace(")", "",$translate);
                          $translate=trim($translate);
                          $translate=(float)$translate;
                          $base_x=(float)$geometricos[$l]["x"]+$translate;
                          $base_y=(float)$geometricos[$l]["y"];
                          $geometricos[$l]["x"]=0;
                          $geometricos[$l]["y"]=0;
                          $ancho_caja=(float)$geometricos[$l]["width"];
                          $alto_caja=(float)$geometricos[$l]["height"];
                           if($ancho_caja!=0)
                            $p=$alto_caja/$ancho_caja;
                          else
                            $p=0;
                          if(!$ubicaciones[$k]['data-p'])
                             $ubicaciones[$k]->addAttribute('data-p',round($p,2));
                          else
                            $ubicaciones[$k]['data-p']=round($p,2);
                          // $ubicaciones[$k]->addAttribute('data-p',round($p,2));
                          unset($ubicaciones[$k]["transform"]);
                        }                      
                    }
                    break;
                }
              }
              if($bandera_transform==1){
                if(isset($base_x)){
                  $ss='<svg x="'.$base_x.'" y="'.$base_y.'" width="'.abs($ancho_caja).'" height="'.abs($alto_caja).'" style="overflow:visible">';
                  $ss.=$ubicaciones[$k]->asXmL();
                  $ss.='</svg>';
                  $svg_procesado_rect.=$ss;
                }
                else{
                  dd($ubicaciones[$k]);
                }
                
              }
            
            
         }       
      }
    }

    $fp = fopen('rect_pros_edificT'.date('YmdHis').'.svg', 'w');
    fwrite($fp,$svg_procesado_rect);
    fclose($fp);
  }

  public function analisis_geometrico_dummy(){
    //fase 1 de los poligonos
    $svg=new XmlSvg();
    $mapa=asset('public/mapa_utm_prepross.svg');
    $svg->init($mapa);
    $targets=$svg->obj_xml->g[1];

    $svg_procesado='';   
    $svg_procesado_rect='';   
    $svg_procesado_polygon='';
    $svg_procesado_path='';
    
    $dimension1=count($targets);
    for($i=0;$i<count($dimension1);$i++){
      //todos los grupos del edificio
      $edificios=$targets[$i]->children();
      $num_edificios=count($edificios);
      for($j=0;$j<$num_edificios;$j++){
        $ubicaciones=$edificios[$j]->children();
        $dom_papa=dom_import_simplexml($edificios[$j]);
        $num_ubicaciones=count($ubicaciones);
        for($k=0;$k<$num_ubicaciones;$k++){
          $modo='';
          $texto=null;
          $geometricos=$ubicaciones[$k]->children();
          $num_geometricos=count($geometricos);
          $bandera_transform=0;

          for($l=0;$l<$num_geometricos;$l++){
            switch($geometricos[$l]->getName()){
              case 'polygon':
                if($modo==''){
                  $bandera_transform=1;
                  $modo='polygon';
                  //obtener los puntos de traslacion
                  $translate=$ubicaciones[$k]["transform"];
                  $translate=str_replace("translate(", "",$translate);
                  $translate=str_replace(")", "",$translate);
                  $translate=trim($translate);
                  $translate=(float)$translate;
                  unset($ubicaciones[$k]["transform"]);                  
                  $puntos=$geometricos[$l]["points"];
                  $bandera_transform=1;              
                  $tmp=explode(' ',$puntos);
                  $pares_tmp=array();
                  foreach($tmp as $el_tmp){
                    if($el_tmp!=''){
                      $pares_tmp[]=$el_tmp;
                    }
                  }

                   //1.-Obtener los pares de origen
                  $p1=explode(',',$pares_tmp[0]);
                  $p2=explode(',',$pares_tmp[1]);
                  $p3=explode(',',$pares_tmp[2]);
                  $p4=explode(',',$pares_tmp[3]);

                  $base_x=(float)$p1[0];
                  $base_y=(float)$p1[1];

                  //calculo de la pendiente, esto para ubicar los marcadores dentro del objeto
                  $denom=(float)$p2[0]-$base_x;
                  if($denom!=0){
                    $num=(float)$p2[1]-$base_y;
                    $p=round($num/$denom,2);
                  }
                  else{
                    $p=0;
                  }
                  $ubicaciones[$k]->addAttribute('data-p',round($p,2));
                  //calculo de la pendiente

                  //calculo del ancho y alto de la caja
                  $ancho_caja=(float)$p2[0]-$base_x;
                  $alto_caja=(float)$p2[1]-$base_y;
                  //calculo del ancho y alto de la caja
                  

                  //2.-Sacar la diferencia entre el par origen y los demas puntos
                  $p2_x=(float)$p2[0]-$base_x;
                  $p2_y=(float)$p2[1]-$base_y;



                  $p3_x=(float)$p3[0]-$base_x;
                  $p3_y=(float)$p3[1]-$base_y;

                  $p4_x=(float)$p4[0]-$base_x;
                  $p4_y=(float)$p4[1]-$base_y;

                  //3.-Al par origen en el punto x sumarle el translate del tag g
                  $base_x+=$translate;
                  $geometricos[$l]["points"]="0,0 ".$p2_x.','.$p2_y.' '.$p3_x.','.$p3_y.' '.$p4_x.','.$p4_y;
                }
                
              break;
              case 'rect':
                if($modo==''){
                  // $bandera_transform=1;
                  $modo='rect';                  
                  if(in_array($edificios[$j]["id"],array("edificio_C","edificio_H","edificio_M","edificio_N"))){                  
                    $bandera_transform=1;                 
                    $translate=$ubicaciones[$k]["transform"];
                    $translate=str_replace("translate(", "",$translate);
                    $translate=str_replace(")", "",$translate);
                    $translate=trim($translate);
                    $translate=(float)$translate;


                    $matrix=$geometricos[$l]["transform"];
                    $matrix=str_replace("matrix(", "",$matrix);
                    $matrix=str_replace(")", "",$matrix);
                    $matrix=trim($matrix);

                    $params=array();
                    $tmp=explode(' ',$matrix);
                    foreach($tmp as $el){
                      if($el!='')
                        $params[]=$el;
                    }



                    
                    if(count($params)!=0){
                      $a=(float)$params[0];
                      $b=(float)$params[1];
                      $c=(float)$params[2];
                      $d=(float)$params[3];
                      $e=(float)$params[4];
                      $f=(float)$params[5];

                      //Paso1 (sumar el translate y el parametro tx de la matrix)
                      $e+=$translate;

                      

                      //Paso 2 hacer una multiplicacion matricial del x y y del rect com la matriz de transformacion
                      $temp_x=(float)$geometricos[$l]["x"];
                      $temp_y=(float)$geometricos[$l]["y"];

                      $base_x=($temp_x*$a)+($temp_y*$c)+$e;
                      $base_y=($temp_x*$b)+($temp_y*$d)+$f;

                      



                      //Paso 3 Modificar la matriz de transformacion del rect manteniendo los campos de rotacion y los de traslacion dejandolos en 0
                      $geometricos[$l]["transform"]="matrix(".$a." ".$b." ".$c." ".$d." 0 0)";



                      //la pendiente es la tangente y se calcula diviendo el seno entre el coseno
                      $ubicaciones[$k]->addAttribute('data-p',round(($b/$a),2));

                      $ancho_caja=(float)$geometricos[$l]["width"];
                      $alto_caja=(float)$geometricos[$l]["height"];
                      unset($ubicaciones[$k]["transform"]);
                      $geometricos[$l]["x"]=0;
                      $geometricos[$l]["y"]=0;
                    }

                    
                  }
                  else{
                    $bandera_transform=0; 
                    $base_x=(float)$geometricos[$l]["x"];
                    $base_y=(float)$geometricos[$l]["y"];
                    $geometricos[$l]["x"]=0;
                    $geometricos[$l]["y"]=0;
                    $ancho_caja=(float)$geometricos[$l]["width"];
                    $alto_caja=(float)$geometricos[$l]["height"];
                     if($ancho_caja!=0)
                      $p=$alto_caja/$ancho_caja;
                    else
                      $p=0;
                    $ubicaciones[$k]->addAttribute('data-p',round($p,2));
                  }
                  
                  
                }                
              break;
              case 'path':
                $bandera_transform=1;
                $modo='path';
                $resultados=$this->parsear_path($geometricos[$l]['d']);
                if(count($resultados['secuencia'])!=0){
                  $tmp=explode(',',$resultados['secuencia'][0]['contenido']);
                  $base_x=(float)$tmp[0];
                  $base_y=(float)$tmp[1];
                  $resultados['secuencia'][0]['contenido']='0,0';
                  $ancho=0;                
                  $altura=0;
                  for($fl=1;$fl<count($resultados['secuencia']);$fl++){
                    if($resultados['secuencia'][$fl]['comando']=='V'){
                      $vf4=(float)$resultados['secuencia'][$fl]['contenido']-$base_x;
                      $resultados['secuencia'][$fl]['contenido']=$vf4;
                    }
                    else{
                      if($resultados['secuencia'][$fl]['comando']=='H'){
                        $vf4=(float)$resultados['secuencia'][$fl]['contenido']-$base_y;
                        $resultados['secuencia'][$fl]['contenido']=$vf4;
                      }
                      else{
                        if($resultados['secuencia'][$fl]['comando']=='h'){
                          if($ancho==0)
                            $ancho=(float)$resultados['secuencia'][$fl]['contenido'];
                        }
                        else{
                          if($resultados['secuencia'][$fl]['comando']=='v'){
                            if($altura==0)
                             $altura=(float)$resultados['secuencia'][$fl]['contenido'];
                          }
                          
                        }
                      }
                    }
                  }
                  $cadena_d='';
                  for($fl=0;$fl<count($resultados['secuencia']);$fl++){
                    $cadena_d.=$resultados['secuencia'][$fl]['comando'].$resultados['secuencia'][$fl]['contenido'];
                  }
                  $geometricos[$l]["d"]=$cadena_d; 

                  //calculo de la pendiente
                  if($ancho!=0)
                    $p=$altura/$ancho;
                  else
                    $p=0;

                  $ancho_caja=$ancho;
                  $alto_caja=$altura;
                }
                if(!$ubicaciones[$k]['data-p'])
                $ubicaciones[$k]->addAttribute('data-p',round($p,2));
                //obtener los puntos de origen                               
              break;
              case 'text':
                if(!$texto)
                $texto=$geometricos[$l];
              break;
            }            
          }

          //creacion del svg anidado
          if($bandera_transform==1){
            //procesamos el texto primero
            // matrix(1 0 0 1 2615.9573 2377.8516)
            if($texto){
              

              if(in_array($edificios[$j]["id"],array("edificio_C","edificio_H","edificio_M","edificio_T","edificio_N"))){  
                /*
                Con el texto es
                Paso 1 restar las coordenadas de traslacion_x-rect_x y traslacion_y-rect_y y 
                Paso 2 luego aplicar los parametros de la matriz de rotacion del rect
                */
                if($modo=='rect'){
                  $srp1=$texto["transform"];
                  $srp1=str_replace("matrix(", "",$srp1);
                  $srp1=str_replace(")", "",$srp1);
                  $srp1=trim($srp1);
                  $params=array();
                  $el_tmp=explode(' ',$srp1);
                  foreach($el_tmp as $xl){
                    if($xl!='')
                     $params[]=$xl;
                  }


                  $nwx=$params[4]-($temp_x);
                  $nwy=$params[5]-$temp_y;

                  $texto["transform"]="matrix(".$params[0]." ".$params[1]." ".$params[2]." ".$params[3]." ".$nwx." ".$nwy.")";

                }

              }
              else{
                $srp1=$texto["transform"];
                $srp1=str_replace("matrix(", "",$srp1);
                $srp1=str_replace(")", "",$srp1);
                $srp1=trim($srp1);
                $params=array();
                $el_tmp=explode(' ',$srp1);
                foreach($el_tmp as $xl){
                  if($xl!='')
                   $params[]=$xl;
                }
                if($modo=='polygon'){
                  $nwx=$params[4]-($base_x-$translate);
                  $nwy=$params[5]-$base_y;
                }
                else{
                $nwx=$params[4]-$base_x;
                $nwy=$params[5]-$base_y;  
                }
                
                $texto["transform"]="matrix(".$params[0]." ".$params[1]." ".$params[2]." ".$params[3]." ".$nwx." ".$nwy.")";                
              }
            }



            $ss='<svg x="'.$base_x.'" y="'.$base_y.'" width="'.abs($ancho_caja).'" height="'.abs($alto_caja).'" style="overflow:visible">';

            
            $ss.=$ubicaciones[$k]->asXmL();
            $ss.='</svg>';

            if($modo=='polygon'){
              $svg_procesado_polygon.=$ss;
            }
            else{
              if($modo=='rect'){
                $svg_procesado_rect.=$ss;
              }
              else{
                $svg_procesado_path.=$ss;
              }
            }

            
          }
          
          

        }
      }      
    }
    // $fp = fopen('polygons'.date('YmdHis').'.svg', 'w');
    // fwrite($fp,$svg_procesado_polygon);
    // fclose($fp);

    $fp = fopen('rect_pros4'.date('YmdHis').'.svg', 'w');
    fwrite($fp,$svg_procesado_rect);
    fclose($fp);

    // $fp = fopen('path'.date('YmdHis').'.svg', 'w');
    // fwrite($fp,$svg_procesado_path);
    // fclose($fp);
  }
}