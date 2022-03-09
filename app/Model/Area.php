<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
     protected $table="area";
    protected $primaryKey="idarea";
     protected $fillable = [
        'nomarea', 'cve_area',
    ];
    public $timestamps = false;

    public function usuario(){
    	return $this->hasMany('App\Model\Usuario','idarea','idarea');
    }

    /*public function permiso(){
    	return $this->belongsToMany('App\Permiso','rolxpermiso','idrol','idpermiso');
    }*/    

}
