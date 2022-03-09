<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Proceso_Carga extends Model
{
     protected $table="proceso_carga";
    protected $primaryKey="idproceso_carga";
     
    public $timestamps = true;

    
    /*public function permiso(){
    	return $this->belongsToMany('App\Permiso','rolxpermiso','idrol','idpermiso');
    }*/    

}