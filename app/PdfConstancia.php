<?php
namespace App;

use Anouar\Fpdf\Fpdf;
use App\QrCode;
class PdfConstancia{
	var $beneficiario='MGTI. Jorge Elías Marrufo Muñoz';
    var $modo_participacion='';
	
	var $nomcongreso='';
	var $ancho=280;
	var $largo=210;
	var $comienzo_x_beneficiario=10;
	var $comienzo_y_beneficiario=10;
	var $ancho_caja_beneficiario=250;
	var $altura=2.8;
	var $logo_congreso;
    var $orientacion="L";
    var $titulo_motivo="Certificado";
    var $firmas=array();
    var $logo_piramide="";

	public function make($modo=''){
		$pdf = new Fpdf();
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage($this->orientacion,"Letter");
        $pdf->SetFont('Arial','B',8);
        $pdf->SetLeftMargin(20);
        $pdf->Setaltura($this->altura);               
        $pdf->SetCellMargin(2); 
        $pdf->SetFillColor(255,255,255);

        


        //linea del logo
        $pdf->Image($this->logo_congreso,$this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario,$this->ancho_caja_beneficiario,0);
        //linea del logo


        //linea del nombre del congreso
        if($this->nomcongreso!=''){        
        $pdf->SetFont('Arial','',26);
        $this->comienzo_y_beneficiario+=70;
        $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);        
        $pdf->MultiCell($this->ancho_caja_beneficiario,$this->altura,$pdf->latin_text($this->nomcongreso),0,'C',true);	
        }        
        //linea del nombre del congreso

        //linea del motivo del certificado
        $pdf->SetFont('Arial','',24);
        $this->comienzo_y_beneficiario+=10;
        $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);        
        $pdf->MultiCell($this->ancho_caja_beneficiario,$this->altura,$pdf->latin_text($this->titulo_motivo),0,'C',true);  
        //linea del motivo del certificado

        //linea del nombre del participante
        $this->comienzo_y_beneficiario+=10;
        $pdf->SetFont('Arial','B',12);
        $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);
        $pdf->MultiCell($this->ancho_caja_beneficiario,$this->altura,$pdf->latin_text("Otorgado a "),0,'C',true);
        $this->comienzo_y_beneficiario+=10;
        $pdf->SetFont('Arial','B',24);
        $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);
        $pdf->MultiCell($this->ancho_caja_beneficiario,$this->altura,$pdf->latin_text($this->beneficiario),0,'C',true);
        //linea del nombre del participante

        //linea del nombre del modo de participacion        
        $pdf->SetFont('Arial','B',16);
        $this->comienzo_y_beneficiario+=10;
        $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);
        $pdf->MultiCell($this->ancho_caja_beneficiario,$this->altura,$pdf->latin_text('Por haber participado en el Congreso Novatic 2018'),0,'C',true);
        //linea del nombre del modo de participacion


        //linea de las firmas
        $this->comienzo_y_beneficiario+=50;                
        $this->firmas($pdf);        
        //linea de las firmas  

        //linea del logo
        // $this->comienzo_y_beneficiario+=1;
        // $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);
        // $pdf->Image($this->logo_piramide,$this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario,$this->ancho_caja_beneficiario,0);
        // $this->logo_piramide=asset('public/congress_assets/novatic2018/piramide_utm.png');
        // $pdf->Image($this->logo_piramide,10,10,100);
        //linea del logo      



        if($modo==''){
          //$pdf->AutoPrint(true);
        $pdf->AutoPrint(true);
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

     function Header(){
          
     }

    public function firmas(&$pdf){
        $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);
        $data_boletos=array();
        $widths_boletos=array();        
        $size_boletos=array();
        $aligns_boletos=array();
        $bordes_celdas=array();

        $data_boletos[]=$pdf->latin_text($this->firmas[0]["nombre"]);
        $data_boletos[]="";
        $data_boletos[]=$pdf->latin_text($this->firmas[1]["nombre"]);
        
        $size_boletos[]=12;
        $size_boletos[]=12;
        $size_boletos[]=12;

        $widths_boletos[]=130;
        $widths_boletos[]=20;
        $widths_boletos[]=110;

        $bordes_celdas[]="T";
        $bordes_celdas[]=0;
        $bordes_celdas[]="T";

        $aligns_boletos[]='C';
        $aligns_boletos[]='C';
        $aligns_boletos[]='C';

        $pdf->SetTableFontSizes($size_boletos);
        $pdf->SetWidths($widths_boletos);        
        $pdf->SetAligns($aligns_boletos);
        $pdf->SetTableBorders($bordes_celdas);
        $pdf->SetFontStyles(array('B','B','B'));
        $pdf->Row($data_boletos);

        $this->comienzo_y_beneficiario+=5; 
        $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);
        $data_boletos=array();
        $widths_boletos=array();        
        $size_boletos=array();
        $aligns_boletos=array();
        $bordes_celdas=array();

        $data_boletos[]=$pdf->latin_text($this->firmas[0]["puesto"]);
        $data_boletos[]="";
        $data_boletos[]=$pdf->latin_text($this->firmas[1]["puesto"]);
        
        $size_boletos[]=12;
        $size_boletos[]=12;
        $size_boletos[]=12;

        $widths_boletos[]=130;
        $widths_boletos[]=20;
        $widths_boletos[]=110;

        $bordes_celdas[]=0;
        $bordes_celdas[]=0;
        $bordes_celdas[]=0;

        $aligns_boletos[]='C';
        $aligns_boletos[]='C';
        $aligns_boletos[]='C';

        $pdf->SetTableFontSizes($size_boletos);
        $pdf->SetWidths($widths_boletos);        
        $pdf->SetAligns($aligns_boletos);
        $pdf->SetTableBorders($bordes_celdas);
        $pdf->SetFontStyles(array('B','B','B'));
        $pdf->Row($data_boletos);

    }

}