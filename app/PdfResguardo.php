<?php
namespace App;

use Anouar\Fpdf\Fpdf;

class PdfResguardo{
	var $config;
	var $ancho_total=245;
	var $ancho_numinv=38;
	var $ancho_cant=11;
	var $ancho_descripcion=55;
	var $ancho_marca=20;
	var $ancho_noserie=47;
	var $ancho_modelo=26;
	var $ancho_observaciones=31;
	var $ancho_ubicacion=21;
	var $altura_renglon=4;
	var $orientacion="L";
	var $margen_izquierdo=15;
	var $activos;
	var $comienzo_x;
	var $folio;
	var $comienzo_y=12;
	var $maximo_filas=25;
	var $logo_utm='';
	var $responsable='';
	var $fecha='';
	var $area='';

	//equal heights
	var $widths;
    var $aligns;
	//equal heights

	function SetWidths($w)
	{
	    //Set the array of column widths
	    $this->widths=$w;
	}

	function SetAligns($a)
	{
	    //Set the array of column alignments
	    $this->aligns=$a;
	}

	function CheckPageBreak($h,&$pdf)
    {
    //If the height h would cause an overflow, add a new page immediately
    if($pdf->GetY()+$h>$pdf->PageBreakTrigger)
        $pdf->AddPage($pdf->CurOrientation);
    }

    function NbLines($w,$txt,&$pdf)
	{
	    //Computes the number of lines a MultiCell of width w will take
	    $cw=&$pdf->CurrentFont['cw'];
	    if($w==0)
	        $w=$pdf->w-$pdf->rMargin-$pdf->x;
	    $wmax=($w-2*$pdf->cMargin)*1000/$pdf->FontSize;
	    $s=str_replace("\r",'',$txt);
	    $nb=strlen($s);
	    if($nb>0 and $s[$nb-1]=="\n")
	        $nb--;
	    $sep=-1;
	    $i=0;
	    $j=0;
	    $l=0;
	    $nl=1;
	    while($i<$nb)
	    {
	        $c=$s[$i];
	        if($c=="\n")
	        {
	            $i++;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	            continue;
	        }
	        if($c==' ')
	            $sep=$i;
	        $l+=$cw[$c];
	        if($l>$wmax)
	        {
	            if($sep==-1)
	            {
	                if($i==$j)
	                    $i++;
	            }
	            else
	                $i=$sep+1;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $nl++;
	        }
	        else
	            $i++;
	    }
	    return $nl;
	}

	function Row($data,&$pdf)
	{
	    //Calculate the height of the row
	    $nb=0;
	    for($i=0;$i<count($data);$i++)
	        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i],$pdf));
	    $h=5*$nb;
	    //Issue a page break first if needed
	    $this->CheckPageBreak($h,$pdf);
	    //Draw the cells of the row
	    for($i=0;$i<count($data);$i++)
	    {
	        $w=$this->widths[$i];
	        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
	        //Save the current position
	        $x=$pdf->GetX();
	        $y=$pdf->GetY();
	        //Draw the border
	        $pdf->Rect($x,$y,$w,$h);
	        //Print the text
	        $pdf->MultiCell($w,5,$data[$i],0,$a);
	        //Put the position to the right of the cell
	        $pdf->SetXY($x+$w,$y);
	    }
	    //Go to the next line
	    $pdf->Ln($h);
	}

	public function make($modo=''){
		$pdf = new Fpdf();
        $pdf->SetAutoPageBreak(false);        
        $pdf->SetFont('Arial','B',8);
        $pdf->SetLeftMargin($this->margen_izquierdo);
        $pdf->AddPage($this->orientacion,"Letter");
        // $pdf->SetCellMargin(2); 
        $pdf->SetFillColor(255,255,255);

        
        $this->comienzo_x=$this->margen_izquierdo;

        $ancho_total=$this->ancho_numinv+$this->ancho_cant+$this->ancho_descripcion+$this->ancho_marca+$this->ancho_noserie+$this->ancho_modelo+$this->ancho_observaciones+$this->ancho_ubicacion;

        //imagen
        if($this->logo_utm!=''){
        	$pdf->Image($this->logo_utm,$this->comienzo_x,10,-300);
        }
        //imagen

        //encabezados
        $pivote=$this->comienzo_x;
        $y=$this->comienzo_y;
        $pdf->SetXY($pivote+30,$y);        
        $pdf->MultiCell($ancho_total-30,$this->altura_renglon,"FOLIO No.".$this->folio,0,'R',true); 

        $y=$pdf->GetY()+1;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($ancho_total,$this->altura_renglon,utf8_decode("UNIVERSIDAD TECNOLÓGICA METROPOLITANA"),0,'C',true); 

        $pivote=$this->comienzo_x;
        $y=$pdf->GetY()+1;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($ancho_total,$this->altura_renglon,utf8_decode("DIRECCIÓN DE ADMINISTRACIÓN Y FINANZAS"),0,'C',true); 

        $pivote=$this->comienzo_x;
        $y=$pdf->GetY()+1;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($ancho_total,$this->altura_renglon,utf8_decode("DEPARTAMENTO DE ACTIVOS FIJOS"),0,'C',true); 
        //encabezados

        //datos del area
        $y=$pdf->GetY()+5;
        $pivote=40;
        $pdf->SetXY($pivote,$y);
        $pdf->MultiCell(70,$this->altura_renglon,utf8_decode("ÁREA DE DESTINO DE LOS BIENES:"),0,'C',true);

        $pivote+=70;
        $pdf->SetXY($pivote,$y);
        $pdf->MultiCell(90,$this->altura_renglon,utf8_decode($this->area),'B','C',true);

        $pivote+=95;
        $pdf->SetXY($pivote,$y);
        $pdf->MultiCell(50,$this->altura_renglon,"FECHA: ".$this->fecha,'B','C',true);
        //datos del area

        //primera fila
        $y=$pdf->GetY()+5;
        $pivote=$this->comienzo_x;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_numinv,$this->altura_renglon,"No. Inv.",1,'C',true);   

        $pivote+=$this->ancho_numinv;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_cant,$this->altura_renglon,"CANT.",1,'C',true); 


        $pivote+=$this->ancho_cant;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_descripcion,$this->altura_renglon,"DESCRIPCION DEL BIEN.",1,'C',true);  

        $pivote+=$this->ancho_descripcion;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_marca,$this->altura_renglon,"MARCA",1,'C',true);

        $pivote+=$this->ancho_marca;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_noserie,$this->altura_renglon,"No. SERIE",1,'C',true);  

        $pivote+=$this->ancho_noserie;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_modelo,$this->altura_renglon,"MODELO",1,'C',true);  

        $pivote+=$this->ancho_modelo;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_observaciones,$this->altura_renglon,"OBSERVACIONES",1,'C',true); 

        $pivote+=$this->ancho_observaciones;
        $pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_ubicacion,$this->altura_renglon,"UBICACION",1,'C',true);  
        //primera fila

        $pdf->SetFont('Arial','',8);
        //fila de activos
        $widths=array();
    	$widths[]=$this->ancho_numinv;
    	$widths[]=$this->ancho_cant;
    	$widths[]=$this->ancho_descripcion;
    	$widths[]=$this->ancho_marca;
    	$widths[]=$this->ancho_noserie;
    	$widths[]=$this->ancho_modelo;
    	$widths[]=$this->ancho_observaciones;
    	$widths[]=$this->ancho_ubicacion;
    	$this->SetWidths($widths);

    	$aligns=array();
    	$aligns[]='L';
    	$aligns[]='C';
    	$aligns[]='L';
    	$aligns[]='L';
    	$aligns[]='L';
    	$aligns[]='L';
    	$aligns[]='L';
    	$aligns[]='L';
    	$this->SetAligns($aligns);

        foreach($this->activos as $el_activo){
        	$data=array();
        	$data[]=$el_activo->clave_interna;
        	$data[]=$el_activo->cantidad;
        	$data[]=$el_activo->descripcion;
        	$data[]=$el_activo->marca;
        	$data[]=$el_activo->no_serie;
        	$data[]=$el_activo->modelo;
        	$data[]="";
        	$data[]="G-118";

        	// $y=$pdf->GetY();
        	// $pivote=$this->comienzo_x;
        	// $pdf->SetXY($pivote,$y);        
	        // $pdf->MultiCell($this->ancho_numinv,$this->altura_renglon,$el_activo->clave_interna,1,'L',true);   

	        // $pivote+=$this->ancho_numinv;
	        // $pdf->SetXY($pivote,$y);        
	        // $pdf->MultiCell($this->ancho_cant,$this->altura_renglon,$el_activo->cantidad,1,'C',true); 


	        // $pivote+=$this->ancho_cant;
	        // $pdf->SetXY($pivote,$y);        
	        // $pdf->MultiCell($this->ancho_descripcion,$this->altura_renglon,$el_activo->descripcion,1,'L',true);  

	        // $pivote+=$this->ancho_descripcion;
	        // $pdf->SetXY($pivote,$y);        
	        // $pdf->MultiCell($this->ancho_marca,$this->altura_renglon,$el_activo->marca,1,'L',true);

	        // $pivote+=$this->ancho_marca;
	        // $pdf->SetXY($pivote,$y);        
	        // $pdf->MultiCell($this->ancho_noserie,$this->altura_renglon,$el_activo->no_serie,1,'L',true);  

	        // $pivote+=$this->ancho_noserie;
	        // $pdf->SetXY($pivote,$y);        
	        // $pdf->MultiCell($this->ancho_modelo,$this->altura_renglon,$el_activo->modelo,1,'L',true);  

	        // $pivote+=$this->ancho_modelo;
	        // $pdf->SetXY($pivote,$y);        
	        // $pdf->MultiCell($this->ancho_observaciones,$this->altura_renglon,"",1,'C',true);
	        $this->Row($data,$pdf);


        }
        //fila de activos

        //fila de termino
        $y=$pdf->GetY();
        $pivote=$this->comienzo_x;        
    	$pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($ancho_total,$this->altura_renglon,'',1,'C',true);   

        $pdf->SetFont('Arial','B',8);
        $y=$pdf->GetY();
        $pivote=$this->comienzo_x;        
    	$pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_numinv+$this->ancho_cant+$this->ancho_descripcion,$this->altura_renglon,'final de los datos','LB','C',true); 

        $pdf->SetFont('Arial','B',8);     
        $pivote+=$this->ancho_numinv+$this->ancho_cant+$this->ancho_descripcion;        
    	$pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_marca+$this->ancho_noserie,$this->altura_renglon,'final de los datos','B','C',true);

        $pdf->SetFont('Arial','B',8);        
        $pivote+=$this->ancho_marca+$this->ancho_noserie;
    	$pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($this->ancho_modelo+$this->ancho_observaciones+$this->ancho_ubicacion,$this->altura_renglon,'final de los datos','BR','C',true); 
        //fila de termino

        //filas vacias
        $flls=count($this->activos)+2;
        for($i=$flls+1;$i<=$this->maximo_filas;$i++){
        	$y=$pdf->GetY();
        	$pivote=$this->comienzo_x;
        	$pdf->SetXY($pivote,$y);        
	        $pdf->MultiCell($this->ancho_numinv,$this->altura_renglon,"",1,'C',true);   

	        $pivote+=$this->ancho_numinv;
	        $pdf->SetXY($pivote,$y);        
	        $pdf->MultiCell($this->ancho_cant,$this->altura_renglon,"",1,'C',true); 


	        $pivote+=$this->ancho_cant;
	        $pdf->SetXY($pivote,$y);        
	        $pdf->MultiCell($this->ancho_descripcion,$this->altura_renglon,"",1,'L',true);  

	        $pivote+=$this->ancho_descripcion;
	        $pdf->SetXY($pivote,$y);        
	        $pdf->MultiCell($this->ancho_marca,$this->altura_renglon,"",1,'L',true);

	        $pivote+=$this->ancho_marca;
	        $pdf->SetXY($pivote,$y);        
	        $pdf->MultiCell($this->ancho_noserie,$this->altura_renglon,"",1,'C',true);  

	        $pivote+=$this->ancho_noserie;
	        $pdf->SetXY($pivote,$y);        
	        $pdf->MultiCell($this->ancho_modelo,$this->altura_renglon,"",1,'L',true);  

	        $pivote+=$this->ancho_modelo;
	        $pdf->SetXY($pivote,$y);        
	        $pdf->MultiCell($this->ancho_observaciones,$this->altura_renglon,"",1,'C',true);

	        $pivote+=$this->ancho_observaciones;
	        $pdf->SetXY($pivote,$y);        
	        $pdf->MultiCell($this->ancho_ubicacion,$this->altura_renglon,"",1,'C',true);

        }
        //filas vacias

        

        //declaracion
        $y=$pdf->GetY()+5;
        $pivote=$this->comienzo_x;        
    	$pdf->SetXY($pivote,$y);        
        $pdf->MultiCell($ancho_total,$this->altura_renglon,$this->config->declarativa,0,'L',true);   
        //declaracion


        //renglon de firmas
        $y=$pdf->GetY()+15;
        $pivote=$this->comienzo_x;        
    	$pdf->SetXY($pivote,$y);
        $pdf->MultiCell($this->ancho_numinv+$this->ancho_cant+$this->ancho_descripcion,$this->altura_renglon,utf8_decode($this->config->jefe_activos),0,'L',true);

        $pdf->SetFont('Arial','',8);
        $y=$pdf->GetY();
        $pivote=$this->comienzo_x;  
        $pdf->SetXY($pivote,$y);
        $pdf->MultiCell($this->ancho_numinv+$this->ancho_cant+$this->ancho_descripcion,$this->altura_renglon,utf8_decode($this->config->puesto_jefe_activos),0,'L',true);

         $pivote+=$this->ancho_numinv+$this->ancho_cant+$this->ancho_descripcion;  
         $pdf->SetXY($pivote,$y);
         $pdf->MultiCell($this->ancho_marca+$this->ancho_noserie,$this->altura_renglon,'Vo, Bo.',0,'L',true);

         $pivote+=$this->ancho_marca+$this->ancho_noserie;
         $pdf->SetXY($pivote,$y);
         $pdf->MultiCell($this->ancho_modelo+$this->ancho_observaciones+$this->ancho_ubicacion,$this->altura_renglon,utf8_decode($this->responsable),0,'R',true);
        //renglon de firmas

         //renglon del codigo de formato
         $y=$pdf->GetY();
         $pivote=$this->comienzo_x;        
    	 $pdf->SetXY($pivote,$y);
         $pdf->MultiCell($ancho_total,$this->altura_renglon,$this->config->codigo_formato,0,'R',true);

         $y=$pdf->GetY();
         $pivote=$this->comienzo_x;        
    	 $pdf->SetXY($pivote,$y);
         $pdf->MultiCell($ancho_total,$this->altura_renglon,$this->config->revision_resguardo,0,'R',true);
         //renglon del codigo de formato

        if($modo==''){
        $pdf->Output('Personalizador.pdf','I');
        exit;    
        }
        else{
            if($modo=='S'){
                $pdfdoc = $pdf->Output('', 'S');
                return $pdfdoc;
            }
        }


	}
}