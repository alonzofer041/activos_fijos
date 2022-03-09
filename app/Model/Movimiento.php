<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table="movimiento";
    protected $primaryKey="id_movimiento";
    public $timestamps=false;
}
