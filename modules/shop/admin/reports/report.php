<?php
 
require('fpdf.php');
$base_path = $_SERVER['DOCUMENT_ROOT'];
include $base_path . '/php/databaseconnection.php';

class Report_PDF extends FPDF {

    var $ln_height;
    var $ln_width;
    var $total;
    var $report_title;
    var $subtitle;
	 

    function Report_PDF($orientation = 'P', $unit = 'mm', $format = 'A4') {
        $lh = 6;
        parent::FPDF($orientation, $unit, $format);
        $this->ln_height = $lh;
		
    }

    //Page header
    function Header() {
		$header_image_height = db_get_single_value('SELECT `VALUE` FROM configuration WHERE `NAME` = "SHOP_REPORT_HEADER_IMAGE_HEIGHT"');		 
        //Logo
        $this->Image('../../../../images/shop/picklistlogo.jpg', 12, 12, $header_image_height);
        //$this->Image('background.jpg',5,40,200);
        //Arial bold 15
        //Move to the right
        //Title
        $this->SetY(10);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Creation date: ' . date('jS F Y'), '', 1, 'R');
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 30, $this->report_title, '', 0, 'C');
        $this->Ln(6);
        if ($this->subtitle) {
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, $this->subtitle, '', 1, 'C');
            $this->Ln(6);
        }
        else
            $this->Ln(16);
        //Line break
    }

    //Page footer
    function Footer() {
        //Position at 1.5 cm from bottom
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        //Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}

?>