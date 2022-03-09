<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
     protected $table="rol";
    protected $primaryKey="idrol";
     protected $fillable = [
        'nomrol', 'cve_rol',
    ];
    public $timestamps = false;

    public function usuario(){
    	return $this->hasMany('App\Model\Usuario','idrol','idrol');
    }

    public function permiso(){
    	return $this->belongsToMany('App\Model\Permiso','rolxpermiso','idrol','idpermiso');
    }    

}
