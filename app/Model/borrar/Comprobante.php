<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;


class Comprobante extends Model
{
    protected $table = "comprobante";
    
    protected $primaryKey = 'idcomprobante';

     public $timestamps = false;
}
