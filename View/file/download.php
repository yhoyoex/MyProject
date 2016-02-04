<?php

require('lib/fpdf/fpdf.php');
require('lib/fpdi/fpdi.php');
$data = $this->res ;
class PDF extends FPDF {
	function head($data){
		$this->SetFont('Arial','I',8);
		$this->Ln(1);
		$this->SetFont('Arial','B',14);
		$this->Ln(1);
		$this->Cell(0,20,$data['file_name'],'0',1,'C');
		$this->Ln(5);
	}
	function Footer() {
		$this->SetY(-15);
		$this->Line(10,$this->GetY(),200,$this->GetY());
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'L');
		$this->Cell(0,10,'Printed : ' .date('d/m/Y').' by '. $_SESSION['username'],0,0,'R');
	}

	function file_detail($data) {
		$this->Line(14,$this->GetY(),195,$this->GetY());
		$this->Ln(3);
	    $this->SetFont('Arial','B','10');
		$this->SetFillColor(255,255,255);
		$this->SetDrawColor(0,0,0);
		$this->Cell(45,8,'File Name','0',0,'L');
		$this->SetFont('Arial','','10');
		$this->MultiCell(130,8,$data['file_name'],0,1,'L');
		$this->Ln(5);
		$this->Line(14,$this->GetY(),195,$this->GetY());
		$this->Ln(3);
		$this->SetFont('Arial','B','10');
		$this->Cell(45,8,'Description','0',0,'L');
		$this->SetFont('Arial','','9');
		$this->MultiCell(130,8,$data['description'],0,1,'L');
		$this->Ln(5);
		$this->Line(14,$this->GetY(),195,$this->GetY());
		$this->Ln(3);
		$this->SetFont('Arial','B','10');
		$this->Cell(45,8,'Tags','0',0,'L');
		$this->SetFont('Arial','','9');
		$tag = '#'. str_replace(",", "  #", $data["tag"]);
		$this->MultiCell(130,8,$tag,0,1,'L');
		$this->Ln(5);
		$this->Line(14,$this->GetY(),195,$this->GetY());
		$this->Ln(3);
	}

	function attachment($data) {
		foreach($data['images'] as $images) {
			$filename = str_replace(" ", "%20", $images["img_name"]);
			$this->AddPage();
			$this->Image( URL. "/public/file/files/".$filename,10,14,190);
    	}
	}
}

$pdf = new PDF();
$pdf->SetMargins(15, 10);
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->head($data);
$pdf->file_detail($data);
$pdf->attachment($data);

ob_end_clean();
ob_start();

$pdf->Output($data['file_name'].'.pdf','I');
?>
