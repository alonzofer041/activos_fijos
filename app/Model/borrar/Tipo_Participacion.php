<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\Model\Congreso;

class Tipo_Participacion extends Model
{
    protected $table = "tipo_participacion";
    
    protected $primaryKey = 'idtipo_participacion';

     public $timestamps = false;

public function congreso()
   {   	    
        return $this->belongsTo('App\Model\Congreso','idcongreso', 'idcongreso');
   }  
}
