<?php
namespace App;

use Anouar\Fpdf\Fpdf;
use App\QrCode;
class PdfPersonalizador{
	var $beneficiario='MGTI. Jorge Elías Marrufo Muñoz';
	var $modo_participacion='';
	var $nomcongreso='';
	var $ancho=110;
	var $largo=140;
	var $comienzo_x_beneficiario=10;
	var $comienzo_y_beneficiario=10;
	var $ancho_caja_beneficiario=100;
	var $altura=2.8;
	var $logo_congreso;
	var $idregistro=0;

	public function make($modo=''){
		$pdf = new Fpdf();
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage("P",array($this->ancho,$this->largo));
        $pdf->SetFont('Arial','B',8);
        $pdf->SetLeftMargin(20);
        $pdf->Setaltura($this->altura);               
        $pdf->SetCellMargin(2); 
        $pdf->SetFillColor(255,255,255);


        //linea del logo
        $pdf->Image($this->logo_congreso,$this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario,90,0);
        //linea del logo


        //linea del nombre del congreso
        if($this->nomcongreso!=''){        
        $pdf->SetFont('Arial','B',16);
        $this->comienzo_y_beneficiario+=30;
        $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);        
        $pdf->MultiCell($this->ancho_caja_beneficiario,$this->altura,$pdf->latin_text($this->nomcongreso),0,'C',true);	
        }        
        //linea del nombre del congreso

        //linea del nombre del participante
        $this->comienzo_y_beneficiario+=30;
        $pdf->SetFont('Arial','B',14);
        $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);
        $pdf->MultiCell($this->ancho_caja_beneficiario,$this->altura,$pdf->latin_text($this->beneficiario),0,'C',true);
        //linea del nombre del participante

        //linea del nombre del modo de participacion
        
        $pdf->SetFont('Arial','B',14);
        $this->comienzo_y_beneficiario+=10;
        $pdf->SetXY($this->comienzo_x_beneficiario,$this->comienzo_y_beneficiario);
        $pdf->MultiCell($this->ancho_caja_beneficiario,$this->altura,$pdf->latin_text($this->modo_participacion),0,'C',true);
        //linea del nombre del modo de participacion

        //linea del Qr
        //QR
        $pdf->ln(2);
        $y_qr=$pdf->GetY();        
        // $qrcode = new QRcode(action('ElectronicAccessController@access',array('modo'=>'B','folio'=>$this->url)), 'H');
        $datos=action('RegistroController@access_get',genera_hashids($this->idregistro));
        $qrcode = new QRcode($datos,'H');
        $qrcode->displayFPDF($pdf,$this->comienzo_x_beneficiario+20,$y_qr,50);
        $pdf->SetFillColor(255,255,255);
        //QR
        //linea del Qr

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

}