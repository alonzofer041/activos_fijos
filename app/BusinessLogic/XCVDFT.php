<?php

namespace App\BusinessLogic;
use Illuminate\Support\Facades\DB;
use App\Model\Recibo;


class XCVDFT{

	var $base=2019;
	var $salt='SFC@#!=1105';
	var $cipher='AES-128-CBC';
	var $iv='ASCFDGBFHCTRWE';
	var $tamanio_block=10000;


	function num_to_letters($num)
	{
	    $num -= 1;
	    $letter = chr(($num % 26) + 97);
	    $letter .= (floor($num/26) > 0) ? str_repeat($letter, floor($num/26)) : '';
	    return strtoupper($letter); 
	}

	function pkcs7_pad($data, $size)
	{
	    $length = $size - strlen($data) % $size;
	    return $data . str_repeat(chr($length), $length);
	}

	function pkcs7_unpad($data)
	{
	    return substr($data, 0, -ord($data[strlen($data) - 1]));
	}

	/*
	$stream = fopen('php://memory','r+');
    fwrite($stream, $string);
	*/

	public function encrypt($objeto){		
		$llave=$this->genera_nonce($objeto->fecha);
		$encrypted=$this->pkcs7_pad(openssl_encrypt(
				    $objeto->contenido, // padded data
				    $this->cipher,        // cipher and mode
				    $llave,               // secret key
				    0,                    // options (not used)
				    $this->pkcs7_pad($this->iv,16)            // initialisation vector
				),16);
		$result=new \StdClass();
		$result->llave=$llave;		
		$result->encrypted=base64_encode($encrypted);		
		return $result;
	}
 //    public function encrypt($objeto){        
 //        $llave=$this->genera_nonce($objeto->fecha);
	// 	$temp = tmpfile();		
	// 	fwrite($temp,$objeto->contenido);
	// 	fseek($temp, 0);
	// 	$final='';
	// 	while (!feof($temp)) {			    
 //                $plaintext = fread($temp, 16 * $this->tamanio_block);
 //                $ciphertext = $this->pkcs7_pad(openssl_encrypt($plaintext,$this->cipher,$llave,0,$this->pkcs7_pad($this->iv,16)),16);
 //                // Use the first 16 bytes of the ciphertext as the next initialization vector
 //                //$iv = substr($ciphertext, 0, 16);                
 //                $final.=base64_encode($ciphertext);
 //        }
	// 	fclose($temp); // this removes the file
        	
	// 	// $llave=$this->genera_nonce($objeto->fecha);
	// 	// $encrypted=$this->pkcs7_pad(openssl_encrypt(
	// 	// 		    $objeto->contenido, // padded data
	// 	// 		    $this->cipher,        // cipher and mode
	// 	// 		    $llave,               // secret key
	// 	// 		    0,                    // options (not used)
	// 	// 		    $this->pkcs7_pad($this->iv,16)            // initialisation vector
	// 	// 		),16);

	// 	$result=new \StdClass();
	// 	$result->llave=$llave;		
	// 	$result->encrypted=$final;		
	// 	return $result;
	// }

	// public function descrypt($objeto){
	// 	$pt1=$this->genera_nonce($objeto->fecha,0);		

	// 	$pt2=0;
	// 	for($i=1;$i<=100;$i++){
	// 		if($objeto->h3==md5($pt1.$i)){
	// 			$pt2=$i;
	// 		}
	// 	}

	// 	$llave=$pt1.$pt2;
	// 	$temp = tmpfile();
	// 	fwrite($temp,$objeto->h4);
	// 	fseek($temp, 0);		
	// 	$final='';
	// 	while (!feof($temp)) {
 //                $ciphertext = fread($temp, 16 * ($this->tamanio_block + 1)); // we have to read one block more for decrypting than for encrypting


 //            	$decrypted = $this->pkcs7_unpad(openssl_decrypt(
	// 					    base64_decode($ciphertext),
	// 					    $this->cipher,
	// 					    $llave,
	// 					    0,
	// 					    $this->pkcs7_unpad($this->iv)
	// 					));

 //               $final.=$decrypted;
 //        }
 //        fclose($temp);

	// 	// $decrypted = $this->pkcs7_unpad(openssl_decrypt(
	// 	// 		    base64_decode($objeto->h4),
	// 	// 		    $this->cipher,
	// 	// 		    $llave,
	// 	// 		    0,
	// 	// 		    $this->pkcs7_unpad($this->iv)
	// 	// 		));	
	// 	$resultado=new \StdClass();
	// 	$resultado->llave=$llave;		
	// 	$resultado->docto=$final;
	// 	return $resultado;
	// }

	public function descrypt($objeto){
		$pt1=$this->genera_nonce($objeto->fecha,0);		

		$pt2=0;
		for($i=1;$i<=100;$i++){
			if($objeto->h3==md5($pt1.$i)){
				$pt2=$i;
			}
		}

		$llave=$pt1.$pt2;			
		$decrypted = $this->pkcs7_unpad(openssl_decrypt(
				    base64_decode($objeto->h4),
				    $this->cipher,
				    $llave,
				    0,
				    $this->pkcs7_unpad($this->iv)
				));	
		$resultado=new \StdClass();
		$resultado->llave=$llave;		
		$resultado->docto=$decrypted;
		return $resultado;
	}

	public function genera_nonce($fecha='',$revert=1){
		if($fecha=='')
			$fecha=date('Y-m-d');

		$tmp=explode('-',$fecha);
		$banio=$this->base-((int)$tmp[0]);
		$bmes=(int)$tmp[1];
		$bdia=(int)$tmp[2];

		$letra_anio=$this->num_to_letters($banio+1);
		$letra_mes=$this->num_to_letters($bmes);		
		$letra_dia=$this->num_to_letters($bdia);

		
		if($revert==1){
			$num=rand(1,100);
			return $letra_anio.$letra_mes.$letra_dia.$num;	
		}
		else{
		  return $letra_anio.$letra_mes.$letra_dia;		
		}
		
	}

	public function registrar_firma($objeto){
		$firma=new Recibo();		
		$ext=$this->encrypt($objeto);
		// //docto
		$firma->h1=md5($objeto->contenido);
		// //usuario
		$firma->h2=md5($objeto->idusuario.$this->salt);		
		//llave
		$firma->h3=md5($ext->llave);		
		//docto
		$firma->h4=$ext->encrypted;		
		$firma->fecha=$objeto->fecha;
		$firma->save();
		return $firma;
	}

	public function recuperar_firma($objeto){
		$firma=Recibo::find($objeto->idrecibo);		
		$tmp=explode(' ',$firma->fecha);
		$firma->fecha=$tmp[0];				
		$ext=$this->descrypt($firma);		
		return $ext;
	}

}