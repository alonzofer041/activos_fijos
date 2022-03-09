<?php
    namespace App;
    use Anouar\Fpdf\Fpdf;
    class PdfBitacora{
        var $altura_renglon=4;
        // var $ancho_activo=82;
        // var $ancho_movimiento=62;
        // var $ancho_destino=43;
        // var $ancho_motivo=63;
        var $ancho_activo=30;
        var $ancho_clave=32;
        var $ancho_cantidad=15;
        var $ancho_precio=20;
        var $ancho_movimiento=24;
        var $ancho_area=30;
        var $ancho_origen=35;
        var $ancho_destino=35;
        var $ancho_area_destino=30;
        // var $ancho_motivo=36;
        var $ancho_total=255;
        var $comienzo_x;
        var $comienzo_y=12;
        var $folio;
        var $orientacion="L";
        var $margen_izquierdo=15;
        var $movimientos;
        var $tamanioHoja="Letter";
        var $logoUTM="";

        var $widths;
        var $aligns;

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
        $pdf->AddPage($pdf->CurOrientation,"Letter");
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
	    $h=5*6;
	    //Issue a page break first if needed
	    $this->CheckPageBreak($h,$pdf);
	    //Draw the cells of the row
	    for($i=0;$i<count($data);$i++)
	    {
            if($i==6 || $i==9){
                $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                $x=$pdf->GetX();
                $y=$pdf->GetY()+4;
                $w=$this->widths[$i];
                $pdf->SetXY($x-$w,$y);
                if($data[$i]==null){
                    $pdf->MultiCell($w,5,"Fecha del movimiento: No aplica",0,$a);
                }
                else{
                    $pdf->MultiCell($w,5,"Fecha del movimiento: ".$data[$i],0,$a);
                }
                $pdf->SetXY($x,$y-4);
            }
            else{
                $w=$this->widths[$i];
                $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                //Save the current position
                $x=$pdf->GetX();
                $y=$pdf->GetY();
                //Draw the border
                $pdf->Rect($x,$y,$w,$h);
                //Print the text
                if($data[$i]==null){
                    $pdf->MultiCell($w,5,"No aplica",0,$a);
                }
                else{
                    $pdf->MultiCell($w,5,utf8_decode($data[$i]),0,$a);
                }
                // $pdf->MultiCell($w,5,utf8_decode($data[$i]),0,$a);
                //Put the position to the right of the cell
                $pdf->SetXY($x+$w,$y);
            }
	    }
	    //Go to the next line
	    $pdf->Ln($h);
	}

        public function hacerPDF(){
            setlocale(LC_ALL,"es_MX.UTF-8");
            date_default_timezone_set("America/Merida");
            $pdf=new Fpdf();
            $pdf->AddPage($this->orientacion,$this->tamanioHoja);
            $pdf->SetFont('Arial','',8);
            $pdf->SetLeftMargin($this->margen_izquierdo);
            $pdf->SetFillColor(255,255,255);
            $this->comienzo_x=$this->margen_izquierdo;
            // $ancho_total=$this->ancho_activo+$this->ancho_movimiento+$this->ancho_destino+$this->ancho_motivo;
            // $ancho_total=$this->ancho_activo+$this->ancho_movimiento+$this->ancho_destino+$this->ancho_clave+$this->ancho_cantidad+$this->ancho_precio+$this->ancho_origen;
            if($this->logoUTM!=""){
                $pdf->Image($this->logoUTM,$this->comienzo_x,10,-300);
            }
            $pivote=$this->comienzo_x;
            $y=$this->comienzo_y;
            $pdf->SetXY($pivote+30,$y);
            $pdf->MultiCell($this->ancho_total-30,8,"FOLIO No.".$this->folio,0,'R',true);

            $y=$pdf->GetY()+1;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_total,$this->altura_renglon,utf8_decode("UNIVERSIDAD TECNOLÓGICA METROPOLITANA"),0,"C",true);
 
            $pivote=$this->comienzo_x;
            $y=$pdf->GetY()+1;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_total,$this->altura_renglon,utf8_decode("DIRECCIÓN DE ADMINISTRACIÓN Y FINANZAS"),0,"C",true);

            $pivote=$this->comienzo_x;
            $y=$pdf->GetY()+1;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_total,$this->altura_renglon,utf8_decode("DEPARTAMENTO DE ACTIVOS FIJOS"),0,"C",true);
            
            //===========FILA PENDIENTE===========//
            $pivote=$this->comienzo_x;
            $y=$pdf->GetY()+2;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_total,$this->altura_renglon,utf8_decode("BITÁCORA DE MOVIMIENTOS DE ACTIVOS FIJOS"),0,"L",true);

            $pivote+=200;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell(50,$this->altura_renglon,"Fecha: ".strftime("%d de %B de %Y"),"B","L",true);

            // $pivote+=70;
            // $pdf->SetXY($pivote,$y);
            // $pdf->MultiCell(100,$this->altura_renglon,"","B","C",true);
            // $pdf->Cell(40,10,'Hola xs');
            
            //===========FILA DE COMIENZO DE COLUMNAS=========//

            //===========CADA ANCHO DE COLUMNA ES EL ANCHO DE LA ANTERIOR========//

            //============ADJUNTAR COLUMNAS DE SER NECESARIO==================//
            $pivote=$this->comienzo_x;
            $y=$pdf->GetY()+5;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_activo,$this->altura_renglon,"Activo",1,"C",true);

            $pivote+=$this->ancho_activo;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_clave,$this->altura_renglon,"Clave",1,"C",true);

            $pivote+=$this->ancho_clave;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_cantidad,$this->altura_renglon,"Cantidad",1,"C",true);

            $pivote+=$this->ancho_cantidad;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_precio,$this->altura_renglon,"Precio",1,"C",true);

            $pivote+=$this->ancho_precio;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_movimiento,$this->altura_renglon,"Movimiento",1,"C",true);

            $pivote+=$this->ancho_movimiento;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_origen,$this->altura_renglon,"Origen",1,"C",true);

            $pivote+=$this->ancho_origen;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_area,$this->altura_renglon,"Area gestora",1,"C",true);

            $pivote+=$this->ancho_area;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_destino,$this->altura_renglon,"Destino",1,"C",true);

            $pivote+=$this->ancho_destino;
            $pdf->SetXY($pivote,$y);
            $pdf->MultiCell($this->ancho_area_destino,$this->altura_renglon,"Area destino",1,"C",true);
            
            // $pivote+=$this->ancho_activo;
            // $pdf->SetXY($pivote,$y);
            // $pdf->MultiCell($this->ancho_movimiento,$this->altura_renglon,"Movimiento",1,"C",true);
// 
            // $pivote+=$this->ancho_movimiento;
            // $pdf->SetXY($pivote,$y);
            // $pdf->MultiCell($this->ancho_destino,$this->altura_renglon,"Destino",1,"C",true);
// 
            // $pivote+=$this->ancho_destino;
            // $pdf->SetXY($pivote,$y);
            // $pdf->MultiCell($this->ancho_motivo,$this->altura_renglon,"Motivo",1,"C",true);

            //==============CONSTRUCCION DE CADA FILA============//

            $widths=array();
            $widths[]=$this->ancho_activo;
            $widths[]=$this->ancho_clave;
            $widths[]=$this->ancho_cantidad;
            $widths[]=$this->ancho_precio;
            $widths[]=$this->ancho_movimiento;
            $widths[]=$this->ancho_origen;
            $widths[]=$this->ancho_origen;
            $widths[]=$this->ancho_area;
            $widths[]=$this->ancho_destino;
            $widths[]=$this->ancho_destino;
            $widths[]=$this->ancho_area_destino;
            // $widths[]=$this->ancho_activo;
            // $widths[]=$this->ancho_movimiento;
            // $widths[]=$this->ancho_destino;
            // $widths[]=$this->ancho_motivo;
            $this->SetWidths($widths);

            $aligns=array();
            // $aligns[]="C";
            // $aligns[]="C";
            // $aligns[]="C";
            // $aligns[]="C";
            $aligns[]="C";
            $aligns[]="C";
            $aligns[]="C";
            $aligns[]="C";
            $aligns[]="C";
            $aligns[]="C";
            $aligns[]="C";
            $aligns[]="C";
            $aligns[]="C";
            $aligns[]="C";
            $aligns[]="C";
            $this->SetAligns($aligns);

            // foreach($this->movimientos as $movimiento){
            //     $data=array();
            //     $data[]=$movimiento->id_activo;
            //     $data[]=$movimiento->nomtipomov;
            //     $data[]=$movimiento->motivo;
            //     $this->Row($data,$pdf);
            // }

            foreach ($this->movimientos as $movimiento) {
                $data=array();
                $data[]=$movimiento->nombre;
                $data[]=$movimiento->clave_interna;
                $data[]=$movimiento->cantidad;
                $data[]=$movimiento->precio;
                $data[]=$movimiento->nomtipomov;
                $data[]=$movimiento->nombreusuario1;
                $data[]=$movimiento->fecha_movimiento;
                $data[]=ucfirst(strtolower($movimiento->nomarea));
                $data[]=$movimiento->nombreusuario2;
                $data[]=$movimiento->fecha_termino;
                $data[]=ucfirst(strtolower($movimiento->area_destino));
                $this->Row($data,$pdf);
            }

            $pdf->Output();
            exit;
        }
    }