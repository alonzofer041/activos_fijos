<?php
 
namespace App\BusinessLogic\BoMovimientos;
 
use App\Model\Movimientos\Mantenimiento;
 
class BoMantenimiento extends BoSatelite{
	
    public function guardar($objeto,$detalle){
        $baja = new Mantenimiento();
		$baja->fecha_proximo=$objeto->mantenimientofecha;
		$baja->costo=$objeto->mantenimientocosto;
		$baja->iddetallemov=$detalle->iddetalle;
		$baja->save();
    }
}