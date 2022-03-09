<?php

namespace App\Model\Movimientos;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table="ventas";
    protected $primaryKey="idventa";
    public $timestamps = false;
}
