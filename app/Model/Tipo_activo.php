<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tipo_activo extends Model
{
    protected $table = 'tipo_activo';
    protected $primaryKey = 'idtipo_activo';
    public $timestamps = false;

    public function activos(){
    	return $this->hasMany('App\Model\Activo', 'idtipo_activo', 'idtipo_activo');
    }

}
