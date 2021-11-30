<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); 

require_once dirname(__FILE__). '/tcpdf/tcpdf.php';

class PDF extends TCPDF
{

    function __construct($orientation = 'P', $unit = 'mm', $format = 'L', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
    }

	public function Header() {
		$this->SetXY(150,12);
		$this->SetFont('helveticaB', '', 11);
		$this->Cell(0, 0, 'Date Printed: '.date('F d, Y'), 0, 0, 'L');
     }
	
	
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetXY(175,-23);
        // Set font
        $this->SetFont('helvetica', 'I', 12);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
    }
}