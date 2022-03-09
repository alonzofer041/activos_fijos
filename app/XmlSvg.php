<?php

namespace App;

class XmlSvg{
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
			$this->obj_xml=simplexml_load_string($this->archivo) or die("Error: Cannot create object");
		}	

	}

	function carga_archivo(){
		$this->obj_xml=simplexml_load_file($this->archivo) or die("Error: Cannot create object");

	}


	
	function dame_ubicaciones($algoritmo){
		// 
		if($algoritmo==1)
			return $this->obj_xml;
	    else
	    	return $this->obj_xml->g;
	}

	function dame_ubicaciones2(){
		//
		// $this->consulta_xpath="/g";
		// $this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
		return $this->obj_xml->g[1]->children();
	// 	return $this->resultados;
	}

	function dame_ubicaciones_g(){
		$this->consulta_xpath="/svg";	
		$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
		dd($this->resultados);
	}

	// function lista_maestros_validos(){
	// 	$this->consulta_xpath='/timetable/teachers/teacher[@idbd!=0]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function lista_asignaturas(){
	// 	$this->consulta_xpath='/timetable/subjects/subject';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function lista_grupos(){
	// 	$this->consulta_xpath='/timetable/classes/class';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }


	// function obten_maestro($id){
	// 	$this->consulta_xpath='/timetable/teachers/teacher[@id="'.$id.'"]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function obten_maestro_bd($id){
	// 	$this->consulta_xpath='/timetable/teachers/teacher[@idbd="'.$id.'"]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function obten_lecciones_maestro($id){
	// 	$this->consulta_xpath='/timetable/lessons/lesson[@teacherids="'.$id.'"]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function obten_asignatura($id){
	// 	$this->consulta_xpath='/timetable/subjects/subject[@id="'.$id.'"]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function obten_idasignaturabd($idxml,$idarea){
	// 	$this->consulta_xpath='/timetable/subjects/subject[@id="'.$idxml.'"]/subject_db[@idarea="'.$idarea.'"]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function obten_idasignaturabd2($idxml){
	// 	$this->consulta_xpath='/timetable/subjects/subject[@id="'.$idxml.'"]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function obten_grupo($id){
	// 	$this->consulta_xpath='/timetable/classes/class[@id="'.$id.'"]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function obten_grupo_bd($id){
	// 	$this->consulta_xpath='/timetable/classes/class[@idbd="'.$id.'"]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function obten_lecciones_grupo($id){
	// 	$this->consulta_xpath='/timetable/lessons/lesson[@classids="'.$id.'"]';
	// 	// $this->consulta_xpath='/timetable/lessons/lesson[contains(@classids,"'.$id.'")]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function obten_salon($id){
	// 	$this->consulta_xpath='/timetable/classrooms/classroom[@id="'.$id.'"]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }

	// function obten_horarios_lecciones($id){
	// 	$this->consulta_xpath='/timetable/cards/card[@lessonid="'.$id.'"]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }


	// /*Para obtener las academias horizontales*/
	// function obten_lecciones_asignatura($id){
	// 	$this->consulta_xpath='/timetable/lessons/lesson[contains(@subjectid,"'.$id.'")]';
	// 	$this->resultados=$this->obj_xml->xpath($this->consulta_xpath);
	// 	return $this->resultados;
	// }
	// /*Para obtener las academias horizontales*/

	// function guardar($archivo=''){
	// 	if($archivo!=''){
	// 		$this->obj_xml->asXml($archivo);
	// 	}
	// 	else{
	// 		return $this->obj_xml->asXml();
	// 	}
	// }
}