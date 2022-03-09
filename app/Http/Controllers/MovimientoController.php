<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Movimiento;
use App\Model\Activo;
use App\Model\Identificacion;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use App\BusinessLogic\BoMovimientos\BoMovimientos;

class MovimientoController extends Controller
{
  public function asignar_activo($tipo_mov){
    $user=Auth::user()->idusuario;
    $personas=User::all();
    $activos=Activo::join('identificacion','identificacion.ididentificacion','=','activo.ididentificacion')
    ->join('resguardo','resguardo.idresguardo','=','activo.idresguardo')
    ->where('resguardo.idusuario',$user)
    ->get();

    $tipomovimiento = $tipo_mov;

    $datos=array();

    $datos['activos']=$activos;
    $datos['personas']=$personas;
    $datos['tipomovimiento']=$tipomovimiento;
    // print_r($datos['']);
    return view("movimientos.asignar_activo")->with($datos);
  }

  public function listamovs(){
    return view('movimientos.listamovs');
  }

  public function elegir_guardar(Request $r){
    $info = $r->all();
    $datos=json_decode($info['datos']);
    $tipomovimiento = $info['tipomovimiento'];
    $motivo = $info['motivo'];
    $bo=new BoMovimientos();
    $bo->guardar($datos,$tipomovimiento,$motivo);
  }  
}