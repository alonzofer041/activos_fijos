<?php

namespace App;

class XmlCfdi{
	var $archivo;
	var $resultados;
	var $consulta_xpath;
	var $obj_xml;

	function init($archivo,$bandera_string=0){
	$this->archivo=$archivo;
	if($bandera_string==0)
	$this->carga_archivo();
	else
	$this->carga_cadena();
	}


	function carga_cadena(){
		if($this->archivo!=''){			
			// $this->obj_xml=simplexml_load_string($this->archivo) or die("Error: Cannot create object");
			$this->obj_xml=new \SimpleXMLElement($this->archivo);
			$this->obj_xml->registerXPathNamespace('cfdi','http://www.sat.gob.mx/cfd/3');
			$this->obj_xml->registerXPathNamespace('nomina12','http://www.sat.gob.mx/nomina12');
            
		}	

	}

	function carga_archivo(){
		$this->obj_xml=simplexml_load_file($this->archivo) or die("Error: Cannot create object");

	}

	function encriptar(){
		$ns = $this->obj_xml->getNamespaces(true);
		dd($this->obj_xml->children($ns['cfdi'])[0]->Comprobante["Fecha"]);
		//rfc
		dd($this->obj_xml->children($ns['cfdi'])->Comprobante->children($ns['cfdi'])->Receptor["Rfc"]);

	}
	
	/*

	<root xmlns:event="http://www.webex.com/schemas/2002/06/service/event">
    <event:event>
        <event:sessionKey></event:sessionKey>
        <event:sessionName>Learn QB in Minutes</event:sessionName>
        <event:sessionType>9</event:sessionType>
        <event:hostWebExID></event:hostWebExID>
        <event:startDate>02/12/2009</event:startDate>
        <event:endDate>02/12/2009</event:endDate>
        <event:timeZoneID>11</event:timeZoneID>
        <event:duration>30</event:duration>
        <event:description></event:description>
        <event:status>NOT_INPROGRESS</event:status>
        <event:panelists></event:panelists>
        <event:listStatus>PUBLIC</event:listStatus>
    </event:event>
    ...
</root>

	$xml = simplexml_load_string($r);
$ns = $xml->getNamespaces(true);

foreach ($xml->children($ns['event'])->event as $skey) {
    $sessionKey = $skey->children($ns['event'])->sessionKey;
    echo $sessionKey;
}
	*/

	function dame_receptor(){
		$final=new \StdClass();
		$this->consulta_xpath="//cfdi:Comprobante/cfdi:Receptor";	
		$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
		if(count($this->resultados)!=0){
			$rf=$this->resultados[0];
			$final->rfc=(string)$rf['Rfc'];
			$final->nombre=(string)$rf['Nombre'];
			$final->usocfdi=(string)$rf['UsoCFDI'];			
			return $final;
		}
		else
			return false;
	}

	function dame_timbrefiscal(){
		$final=new \StdClass();
		$this->consulta_xpath="//cfdi:Comprobante/cfdi:Complemento/tfd:TimbreFiscalDigital";	
		$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
		if(count($this->resultados)!=0){
			$rf=$this->resultados[0];			
			$final->uuid=(string)$rf['UUID'];
			$final->fecha_timbrado=(string)$rf['FechaTimbrado'];						
			return $final;
		}
		else
			return false;
	}

	
}