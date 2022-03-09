<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\Model\Congreso;

class Participacion extends Model
{
    protected $table = "participacion";
    
    protected $primaryKey = 'idparticipacion';

     public $timestamps = false;

// public function congreso()
//    {   	    
//         return $this->belongsTo('App\Model\Congreso','idcongreso', 'idcongreso');
//    }  
}
