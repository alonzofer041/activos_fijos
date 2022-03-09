<?php

namespace App\BusinessLogic;
use Illuminate\Support\Facades\DB;
use App\BusinessLogic\BoGeneric;
use App\Model\Institucion;
use App\Model\Participante;
use App\Model\Congreso;
use App\Model\Participacion;
use App\Model\Usuario;
use App\Model\Datos_Facturacion;
use App\Model\Comprobante;
use App\Model\Grupo_Participacion;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

ini_set('memory_limit', '-1');
ini_set('max_execution_time',600);



class BoParticipante extends BoGeneric{	

	function __construct($contexto){
		$this->context=$contexto;
	}

	function validar_existencia_correo(){
		$par=Usuario::where('email',$this->correo)->first();
		if($par)
			return $par;
		else
			return false;
	}

	function validar_datos_facturacion(){
		$par=Datos_Facturacion::where('rfc',$this->rfc)->first();
		if($par)
			return $par;
		else
			return false;
	}

	function dame_descuento(){
		return 0;
	}



	function obtener_costo_participacion($tipo='PAR'){
		$congreso=Congreso::find($this->idcongreso);
		$costo=$congreso->costos()->with(['tipo_participacion' => function ($query) use ($tipo) {
                   $query->where('cve', $tipo);
                    }])->first();
		return $costo;
	}

	function registrar_usuario(){
		$usuario=new Usuario;
		$usuario->email=$this->correo;
		$usuario->password=bcrypt($this->password);
		$usuario->save();
		return $usuario;
	}

	function crear_grupo(){		
		//1.-Leer y analizar el excel
		// $fuente=file_get_contents($this->excel_grupal->getRealPath());		
		$objectReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objectReader->load($this->excel_grupal->getPathName());
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        //Se obtiene el número máximo de filas
        $highestRow = $objWorksheet->getHighestRow();
        //Se obtiene el número máximo de columnas
        $highestColumn = $objWorksheet->getHighestColumn();
        
        $columna_nombre='A';
        $columna_correo='B';

        $contadorFilas = 2;

        $participantes_hijos=array();

        for($contadorFilas; $contadorFilas<=$highestRow; $contadorFilas++){
        	$tmp=new \StdClass();
        	$nombre=$objWorksheet->getCell($columna_nombre.$contadorFilas)->getFormattedValue();
        	$correo=$objWorksheet->getCell($columna_correo.$contadorFilas)->getFormattedValue();
        	$tmp->nombre=$nombre;
        	$tmp->correo=$correo;
        	$participantes_hijos[]=$tmp;
        }

        //2.-Dado el participante entonces crear el grupo de participacion con el json de los participantes.
        $grupo=new Grupo_Participacion;
        $grupo->idparticipante=$this->idparticipante;
        $grupo->nomgrupo_participacion='';
        $grupo->json=json_encode($participantes_hijos);
        $grupo->status='Registrado';
        $grupo->save();	
        return $grupo;
	}

	function registrar_participante(){
		//0.-Validar la existencia del participante con base al correo.
		$usuario_validar=$this->validar_existencia_correo();		
		if(!$usuario_validar){
			//Si el usuario no existe entonces se crea el participante
			$usuario=$this->registrar_usuario();
			// dd($usuario);
			//1.-Registrar el participante	
			$participante=new Participante;
			// $participante->create();
			$participante->nomparticipante=$this->nombre;
			$participante->status='Registrado';
			$participante->idinstitucion=$this->idinstitucion;

			//2.-Asociar el usuario al participante
			$participante->idusuario=$usuario->idusuario;
			$participante->save();

			$idrfc=0;
			if($this->rfc!=''){
				//3-Verificar si existe el dato de facturacion con base a el rfc y asociarlo
				$datos_facturacion=$this->validar_datos_facturacion();
	            if(!$datos_facturacion){
	            	//como no existen los datos de facturacion entonces los creo
	            	$datos_facturacion=new Datos_Facturacion;
	            	$datos_facturacion->rfc=$this->rfc;
	            	$datos_facturacion->razon_social=$this->razon_social;
	            	$datos_facturacion->direccion_facturacion=$this->direccion_facturacion;
	            	$datos_facturacion->save();
	            	$idrfc=$datos_facturacion->iddatos_facturacion;
	            }
			}

            //4.-Crear el registro de participacion asociandolo a un congreso y un costo del congreso
            $costo=$this->obtener_costo_participacion();            
            $participacion=new Participacion;
            $participacion->costo=$costo->importe;
            $participacion->idtipo_participacion=$costo->idtipo_participacion;
            $participacion->status='Registrada';
            $participacion->idcongreso=$this->idcongreso;
            $participacion->descuento=$this->dame_descuento();
            $participacion->iddatos_facturacion=$idrfc;
            $participante->participacion()->save($participacion);

            //5.-Despues de guardar la participacion en caso de tener un comprobante entonces subirlo y guardarlo en la bd 
            if($this->comprobante_individual!=''){
            	$path = $this->comprobante_individual->store('comprobantes');
	            $comprobante=new Comprobante;
	            $comprobante->idregistro=$participacion->idparticipacion;
	            $comprobante->archivo=$path;
	            $comprobante->origen='Individual';
	            $comprobante->save();
            }
            

            //6.-Si subio un archivo de alumnos entonces pasar a Json los registros leido para pasarlos a la pagina de confirmacion de registro grupal
            if($this->excel_grupal!=''){
            	$this->idparticipante=$participante->idparticipante;
	            $grupo=$this->crear_grupo();

	            //7.-Al crear el grupo entonces crear el comprobante de pago del grupo
	            $path2 = $this->comprobante_grupal->store('comprobantes');
	            $comprobante=new Comprobante;
	            $comprobante->idregistro=$grupo->idgrupo_participacion;
	            $comprobante->archivo=$path2;
	            $comprobante->origen='Grupal';
	            $comprobante->save();

	            //redirigir al paso 2 del registro grupal
            }
            else{
            	//redirigir al paso final del registro
            	return true;
            }
		}//8.-En otro metodo crear el grupo con los participantes asociados.
	}

	public function actualizar_participantes(){
		Grupo_Participacion::where('idgrupo_participacion',$this->idgrupo)->update(["json"=>$this->dts]);
	}
}