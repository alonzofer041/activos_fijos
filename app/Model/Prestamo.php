<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table="prestamo";
    protected $primaryKey="idprestamo";
    public $timestamps=false;
}
