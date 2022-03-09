<?php
 
namespace App\BusinessLogic\BoMovimientos;
 
use App\Model\Movimientos\Baja;
 
class BoBaja extends BoSatelite{
	
    public function guardar($objeto,$detalle){
        $baja = new Baja();
		$baja->fecha_adquisicion=$objeto->bajafecha;
		$baja->fecha_desuso=$objeto->bajafecha;
		$baja->iddetallemov=$detalle->iddetalle;
		$baja->save();
    }
}