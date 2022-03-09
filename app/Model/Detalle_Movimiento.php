<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Detalle_Movimiento extends Model
{
    protected $table="detalle_movimiento";
    protected $primaryKey="id_detalle";
    public $timestamps=false;
}
