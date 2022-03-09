<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\Model\Usuario;
use App\Model\Institucion;
use App\Model\Participacion;

class Participante extends Model
{
    protected $table = "participante";
    
    protected $primaryKey = 'idparticipante';

     public $timestamps = false;

    //  protected $fillable = [
    //     //'nombre', 'correo', 'password', 'idrol', 'codigo', 'estado'
    //     'nomparticipante', 'idinstitucion', 'status'
    // ];

    

 public function usuario()
   {   	    
        return $this->belongsTo('App\Model\Usuario','idusuario', 'idusuario');
   }    

public function institucion()
   {   	    
        return $this->belongsTo('App\Model\Institucion','idinstitucion', 'idinstitucion');
   }  

public function participacion(){
	return $this->hasMany('App\Model\Participacion', 'idparticipante', 'idparticipante');
} 
    
}

 