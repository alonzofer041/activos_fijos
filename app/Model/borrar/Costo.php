<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\Model\Congreso;
use App\Model\Tipo_Participacion;

class Costo extends Model
{
    protected $table = "costo";
    
    protected $primaryKey = 'idcosto';

     public $timestamps = false;

public function congreso()
   {   	    
        return $this->belongsTo('App\Model\Congreso','idcongreso', 'idcongreso');
   }  
public function tipo_participacion()
   {   	    
        return $this->belongsTo('App\Model\Tipo_Participacion','idtipo_participacion', 'idtipo_participacion');
   }   
    
}
