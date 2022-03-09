<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use App\Model\Costo;

class Congreso extends Model
{
    protected $table = "congreso";
    
    protected $primaryKey = 'idcongreso';

        
public function institucion()
   {   	    
        return $this->belongsTo('App\Model\Institucion','idinstitucion', 'idinstitucion');
   }  

public function costos(){
	return $this->hasMany('App\Model\Costo', 'idcongreso', 'idcongreso');
}
    
}