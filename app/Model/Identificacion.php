<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Identificacion extends Model
{
    protected $table = 'identificacion';
    protected $primaryKey = 'ididentificacion';
    public $timestamps = false;

    public function activos(){
    	return $this->belongsTo('App\Model\Activo', 'ididentificacion', 'ididentificacion');
    }

}
