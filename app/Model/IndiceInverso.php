<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IndiceInverso extends Model
{
     protected $table="indice_inverso";
    
    
    public $timestamps = false;

    

    /*public function permiso(){
    	return $this->belongsToMany('App\Permiso','rolxpermiso','idrol','idpermiso');
    }*/    

}