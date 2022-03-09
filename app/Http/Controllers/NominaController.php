<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\XmlCfdi;
use App\Model\Recibo;
use App\BusinessLogic\XCVDFT;


ini_set('memory_limit', '-1');
ini_set('max_execution_time',600);

class NominaController extends Controller
{
	public function procesa_nomina(Request $r){
		if($r->hasFile('zip_nomina')){
		  $archivo=$r->file('zip_nomina');
		  $za = new \ZipArchive(); 
		  $cvdg=new XCVDFT();
		  $sign=new \StdClass();

		  $za->open($archivo);
		  
		  for( $i = 0; $i < $za->numFiles; $i++ ){ 
		  	    $xml=new XmlCfdi();
		  	    $contenido=$za->getFromIndex($i);
		  	    $xml->init($contenido,1);
		  	    $xml->encriptar();
		  	    // $this->crypt_test($contenido);
		  	    $sign=new \StdClass();
		  	    $sign->fecha=date('Y-m-d');
		  	    //
		  	    $sign->contenido=$contenido;		  	    
		  	    $sign->idusuario=Auth::user()->idusuario;
		  	    $cvdg->registrar_firma($sign);
		  	    $xml->init($contenido,1);
		  	    $xml->encriptar();
			    // $stat = $za->statIndex( $i ); 
			    // print_r( basename( $stat['name'] ) . PHP_EOL ); 
			    $receptor=$xml->dame_receptor();
			    if($receptor){
			    echo $receptor->rfc.' '.$receptor->nombre.'<br/>';
			    }

			    $timbre=$xml->dame_timbrefiscal();
			    if($timbre){
			    	echo $timbre->uuid.' '.$timbre->fecha_timbrado.'<br/>';
			    }			    
		  }
		}	
	}

	public function subir_nomina(){
		return view('nomina/subir');
	}

	public function recupera_recibo(){		
		$objeto=new \StdClass();
		$objeto->idrecibo=15;
		$cvdg=new XCVDFT();
		$origen=$cvdg->recuperar_firma($objeto);
		// dd($origen->docto);
		$fp = fopen('nomina'.date('YmdHis').'.xml', 'w');
	    fwrite($fp,$origen->docto);
	    fclose($fp);
		// $xml=new XmlCfdi();
		// $xml->init($origen->docto,1);
		// $receptor=$xml->dame_receptor();
	 //    if($receptor){
	 //    echo $receptor->rfc.' '.$receptor->nombre.'<br/>';
	 //    }
	}
	
	public function crypt_test($docto_xml){
		$cvdg=new XCVDFT();		

		$objeto=new \StdClass();
		$objeto->contenido=$docto_xml;
		$objeto->fecha=date('Y-m-d');
		$objeto->idusuario=Auth::user()->idusuario;
		$firma=$cvdg->registrar_firma($objeto);

		$fuente=new \StdClass();
		$fuente->idrecibo=1;
		$original=$cvdg->recuperar_firma($fuente);
		dd('El original es '.$original->docto);		
	}
}