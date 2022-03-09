<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    protected $table="mantenimiento";
    protected $primaryKey="idmantenimiento";
    public $timestamps=false;
}
