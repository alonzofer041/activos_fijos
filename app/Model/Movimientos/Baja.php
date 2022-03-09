<?php

namespace App\Model\Movimientos;

use Illuminate\Database\Eloquent\Model;

class Baja extends Model
{
    protected $table="baja";
    protected $primaryKey="idbaja";
    public $timestamps = false;
}
