<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;


class Grupo_Participacion extends Model
{
    protected $table = "grupo_participacion";
    
    protected $primaryKey = 'idgrupo_participacion';

     public $timestamps = false;
}
