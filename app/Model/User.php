<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Tabla al que corresponde.
     */
    protected $table='usuario';
    

    /**
     * ID de la tabla de agentes.
     */
    protected $primaryKey='idusuario';

    public $timestamps=false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomusuario', 'apellidos', 'email', 'password', 'telefono', 'gdr_academico','idrol','area','matricula',
        //'nombre', 'curp', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

 public function rol()
    {
        return $this->belongsTo('App\Model\Rol','idrol','idrol');
    }
}
