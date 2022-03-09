<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
     protected $table="permiso";
    protected $primaryKey="idpermiso";
     protected $fillable = [
        'nompermiso', 'cve_permiso',
    ];
    public $timestamps = false;

    public function rol(){
    	return $this->belongsToMany('App\Model\Rol','rolxpermiso','idpermiso','idrol');
    }
}

