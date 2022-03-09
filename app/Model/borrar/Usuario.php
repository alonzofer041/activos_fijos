<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'idusuario';
    protected $table = "usuario";
    protected $fillable = [
        //'nombre', 'correo', 'password', 'idrol', 'codigo', 'estado'
        'nombre', 'correo', 'password', 'idrol'
    ];

    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'codigo'
    ];

    public function participante()
    {
        return $this->hasOne('App\Model\Participante', 'idusuario', 'idusuario');
    }
}
