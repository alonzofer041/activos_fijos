<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table="usuario";
    protected $primaryKey="idusuario";
    protected $fillable = [
        'nomusuario', 'apellidos', 'idrol', 'telefono', 'gdr_academico', 'email', 'password', 'idarea', 'matricula',
    ];
protected $hidden = [
        'password', 'remember_token',
    ];
    public $timestamps = false;
    //public $timestamps = false; -> este es para desactivar el updated_at y el created_at de las fechas de creacion y edicion
     // public function setPasswordAttribute($value)
     //    {
     //        $this->attributes['password']=bcrypt($value);
     //    }
    public function rol(){
    	return $this->belongsTo('App\Model\Rol','idrol','idrol');
    }
    



    public function area(){
        return $this->belongsTo('App\Model\Area','idarea','idarea');
    }

    /*public function propiedad(){
    	return $this->hasMany('App\Propiedad','idusuario','idusuario');
    }*/
}

?>
