<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = "alumno";
    
    protected $primaryKey = 'idalumno';

    protected $connection = 'mysql_pca';

    
    
}