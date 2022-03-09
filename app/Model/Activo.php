<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Activo extends Model
{
    protected $table = 'activo';
    protected $primaryKey = 'idactivo';
    public $timestamps = false;

    protected $fillable = [
        'idubicacion',
       
    ];

    public function tipo_activo(){
    	return $this->belongsTo('App\Model\Tipo_activo', 'idtipo_activo', 'idtipo_activo');
    }

    public function partida(){
    	return $this->belongsTo('App\Model\Partida', 'idpartida', 'idpartida');
    }

    public function identificacion(){
    	return $this->hasOne('App\Model\Identificacion', 'ididentificacion', 'ididentificacion');
    }


}
