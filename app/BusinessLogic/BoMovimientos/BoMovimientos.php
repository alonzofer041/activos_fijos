<?php

namespace App\BusinessLogic\BoMovimientos;

use App\Model\User;
use Illuminate\Support\Facades\Auth;
use App\Model\Activo;
use App\Model\Movimiento;
use App\Model\DetalleMovimiento;
use App\Model\Movimientos\Baja;
use App\BusinessLogic\BoMovimientos\BoBaja;
use App\Model\Movimientos\Venta;
use App\BusinessLogic\BoMovimientos\BoVenta;
use App\Model\Movimientos\Mantenimiento;
use App\BusinessLogic\BoMovimientos\BoMantenimiento;

class BoMovimientos{

	public function guardar($objeto,$tipo,$motivo){
        $movimiento = new Movimiento();
        $movimiento->motivo=$motivo;
        $movimiento->fecha_mov=date('Y-m-d H:i:s');
        $movimiento->id_tipo_mov=$tipo;
        $movimiento->save();

        foreach ($objeto as $obj) {

            // print_r($obj->id);

            $detalle=$this->detalle_movimiento($obj,$movimiento);

            $satelite=$this->satelite($obj,$tipo,$detalle);
        }
	}

	public function detalle_movimiento($obj,$movimiento){
            $idemisor=Auth::user()->idusuario;
            $area_gestora = Auth::user()->idarea;
            
            $detalle = new DetalleMovimiento();
            $detalle->descripcion_motivo = $obj->descripcionmotivo;
            $detalle->clave = $obj->clave;
            $detalle->area_gestora = $area_gestora;
            // $detalle->aprobacion = NULL
            $detalle->observaciones = $obj->observaciones;
            // $detalle->precio = NULL
            $detalle->cantidad = $obj->cantidad;
            $detalle->fecha_termino = $obj->fechatermino;
            $detalle->nueva_ubicacion = $obj->nuevaubicacion;
            $detalle->idemisor = $idemisor;
            // $detalle->idreceptor = NULL
            $detalle->idmovimiento = $movimiento->idmovimiento;
            $detalle->idactivo = $obj->id;
            // $detalle->idestado = NULL
            $detalle->save();
            
            return $detalle;
	}

    public function satelite($obj,$tipo,$detalle){
        switch($tipo){
            case 1:
            $satelite=new BoVenta();
            break;
            case 2:
            $satelite=new BoBaja();
            break;
            case 3:
            $satelite=new BoMantenimiento();
            break;

        }
        $satelite->guardar($obj,$detalle);
    }
}