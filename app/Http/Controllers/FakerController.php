<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model;
use App\Usuario;
use App\Rol;
use App\Area;
use App\Identificacion;
use App\Resguardo;
use App\Status;
use App\Activo;
use App\Ubicacion;
use App\Partida;
use App\Detalle_resguardo;
use App\Tipo_activo;

use Faker\Factory as Faker;


class FakerController extends Controller
{
    public function home(){

    	$faker = Faker::create();

    	$rol=Rol::all();
    	$num_roles=sizeof($rol);
    	$area=Area::all();
    	$num_areas=sizeof($area);
	    $status=Status::all();	
	    $num_estatus=sizeof($status);
	    $ubicacion=Ubicacion::all();	
	    $num_ubicaciones=sizeof($ubicacion);
	    $partida=Partida::all();	
	    $num_partidas=sizeof($partida);
	    $tipo_activo=Tipo_activo::all();	
	    $num_tipo_activos=sizeof($tipo_activo);

    	for($i=1;$i<=48;$i++){
    	  Usuario::create(
			  	         [
			  	        	'nomusuario'=>$faker->firstName,
			  	        	'apellidos'=>$faker->lastName,
			  	        	'idrol'=>$faker->numberBetween(1,$num_roles),
			  	        	'telefono'=>$faker->tollFreePhoneNumber,
			  	        	'gdr_academico'=>$faker->randomElement(['Licenciatura','Doctorado','Maestría','Estudiante']),
					        'email'=>$faker->unique()->safeEmail,
					        'password'=>bcrypt('123456'),
					        'idarea'=>$faker->numberBetween(1,$num_areas),
					        'matricula'=>$faker->unique()->numberBetween(1000000000,99999999),
					        'remember_token' => str_random(10),
					    ]
		    	    );	
    	}

    	Usuario::create(
		  	         [
		  	        	'nomusuario'=>'Carlos',
		  	        	'apellidos'=>'Puc Cauich',
		  	        	'idrol'=>'1',
		  	        	'telefono'=>$faker->tollFreePhoneNumber,
		  	        	'gdr_academico'=>'Estudiante',
				        'email'=>'carlospc402@gmail.com',
				        'password'=>bcrypt('123456'),
				        'idarea'=>'1',
				        'matricula'=>'17090652',
				        'remember_token' => str_random(10),
				    ]
	    	    );	

    	Usuario::create(
		  	         [
		  	        	'nomusuario'=>'Jorge Elías',
		  	        	'apellidos'=>'Marrufo Muñoz',
		  	        	'idrol'=>'1',
		  	        	'telefono'=>$faker->tollFreePhoneNumber,
		  	        	'gdr_academico'=>'Maestría',
				        'email'=>'elias.marrufo.utmetropolitana.edu.mx',
				        'password'=>bcrypt('12345678'),
				        'idarea'=>'1',
				        'matricula'=>$faker->numberBetween(1000000000,99999999),
				        'remember_token' => str_random(10),
				    ]
	    	    );
	    $usuario=Usuario::all();	
	    $num_usuarios=sizeof($usuario);

	    for($i=1;$i<=50;$i++){
    	  Identificacion::create(
			  	         [
			  	        	'no_iden'=>$faker->unique()->numberBetween(100000000,9999999),
			  	        	'cantidad'=>$faker->numberBetween(1,3),
			  	        	'marca'=>$faker->randomElement(['HP','Epson','Kingston','Lenovo','Yamaha']),
			  	        	'modelo'=>$faker->randomLetter.$faker->numberBetween(1,100).$faker->randomLetter.$faker->randomLetter.$faker->numberBetween(1,10),
					        'no_serie'=>$faker->unique()->numberBetween(10000000,999999),
					        'precio_u'=>$faker->numberBetween(1500,10000),
					        'subtotal'=>$faker->numberBetween(1500,10000),
					        'iva'=>'16',
					        'total' =>$faker->numberBetween(1500,10000),
					        'nombre' =>$faker->randomElement(['Laptop','Camara','Escritorio','Silla','Mouse']),
					        'descripcion' => $faker->realText,
					    ]
		    	    );	
    	}
    	$identificacion=Identificacion::all();	
	    $num_identificaciones=sizeof($identificacion);

	    for($i=1;$i<=50;$i++){
    	  Resguardo::create(
			  	         [
			  	        	'idusuario'=>$faker->unique()->numberBetween(1,$num_usuarios),
			  	        	'no_resguardo'=>$faker->unique()->numberBetween(10000000,999999),
			  	        	'fecha'=>$faker->dateTimeBetween('-1 years','now'),
					        'idstatus'=>$faker->numberBetween(1,$num_estatus),
					    ]
		    	    );	
    	}
    	$resguardo=Resguardo::all();	
	    $num_resguardos=sizeof($resguardo);

    	for($i=1;$i<=50;$i++){
    	  Activo::create(
			  	         [
			  	        	'clave_interna'=>'UTM-'.$faker->unique()->numberBetween(10000000,999999),
			  	        	'idtipo_activo'=>$faker->numberBetween(1,$num_tipo_activos),
			  	        	'idresguardo'=>$faker->unique(true)->numberBetween(1,$num_resguardos),
			  	        	'idubicacion'=>$faker->numberBetween(1,$num_ubicaciones),
					        'ididentificacion'=>$faker->unique()->numberBetween(1,$num_identificaciones),
					        'razon_social'=>$faker->randomElement(['HP','Epson','Kingston','Lenovo','Yamaha']),
					        'idpartida'=>$faker->numberBetween(1,$num_partidas),
					        'inventariable'=>$faker->randomElement(['Inventariable','No Inventariable']),
					    ]
		    	    );	
    	}
    	$activo=Activo::all();	
	    $num_activos=sizeof($activo);

	    for($i=1;$i<=50;$i++){
    	  Detalle_resguardo::create(
			  	         [
			  	        	'idresguardo'=>$faker->unique(true)->numberBetween(1,$num_resguardos),
			  	        	'idactivo'=>$faker->unique()->numberBetween(1,$num_activos),
					    ]
		    	    );	
    	}

    }
}
