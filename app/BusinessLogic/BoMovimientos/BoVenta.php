<?php
 
namespace App\BusinessLogic\BoMovimientos;
 
use App\Model\Movimientos\Venta;
 
class BoVenta extends BoSatelite{
	
    public function guardar($objeto,$detalle){
        $venta = new Venta();
		$venta->precio_venta= $objeto->ventaprecio;
		$venta->iddetallemov=$detalle->iddetalle;
		$venta->save();
    }
}