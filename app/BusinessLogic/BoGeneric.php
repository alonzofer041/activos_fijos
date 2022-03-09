<?php

namespace App\BusinessLogic;
use Illuminate\Support\Facades\DB;


class BoGeneric{
    var $context;

    
public function __get($name)
    {
        
        if (array_key_exists($name, $this->context)) {
            return $this->context[$name];
        }
        else{
            return null;
        }
    }
 public function __set($name, $value)
    {        
        $this->context[$name] = $value;
    }
}