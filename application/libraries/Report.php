<?php
if (!defined('BASEPATH')) exit('No direct script access allowed'); 

require_once dirname(__FILE__). '/tcpdf/tcpdf.php';

class Report extends TCPDF
{
	private $reportName;
	private $dateTo = '...';
	private $dateFrom = '...';
	public $top_margin = 62;
	
    function __construct($orientation = 'P', $unit = 'mm', $format = 'L', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
    }
    //Page header
    public function Header() {
        // Logo
		// $image_file = K_PATH_IMAGES.'logo_example.jpg';
        //dirname(__FILE__).
		// Title
        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
       
        $image_file = FCPATH."assets/images/gb-logo.png";
		
        // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
        $this->Image($image_file,20, 15, 35, 35, 'PNG', '', '', true, 300, '');

        // Set font
        $this->SetFont('helvetica', 'I', 11);
     
		$this->Ln(3);
		$this->Cell(0, 0, $this->getAliasRightShift().'Page '.$this->PageNo().'/'.$this->getAliasNbPages(), 0, false, 'R');
		
		$titleSize = 16;
		$subheaderSize = 10;
		$reportTitleSize = 11;
		$normalFontSize = 11;
	   //$this->Cell(0, 0, , 0, 0, 'C');
		$this->Ln(10);
        $this->SetFont('helveticaB', '', $titleSize);
        $this->Cell(0, 0, 'GoodBuy Enterprises', 0, 0, 'C');
		
		$this->Ln();
        $this->SetFont('helvetica', '', $subheaderSize);  
        $this->Cell(0, 0, '2nd Floor D&C Building, Russia St.', 0, 0, 'C');

		$this->Ln();
        $this->SetFont('helvetica', '', $subheaderSize);
        $this->Cell(0, 0, 'Parañaque City, Philippines', 0, 0, 'C');
		
		$this->Ln();
        $this->SetFont('helvetica', '', $subheaderSize);
        $this->Cell(0, 0, 'Contact No.: 09328434905', 0, 0, 'C');
        
		$this->Ln();
        $this->SetFont('helvetica', '', $subheaderSize);
        $this->Cell(0, 0, 'www.goodbuy.com', 0, 0, 'C');
		
		$this->Ln(10);
        $this->SetFont('helvetica', '', $reportTitleSize);
        //$this->Cell(0, 15, 'Report of ', 0, false, 'L', 0, '', 0, false, 'M', 'M');
		$this->Cell(0, 0, urldecode(strtoupper($this->reportName  . ' Report')) , 0, 0, 'C');
		$this->Ln();
        $this->SetFont('helvetica', '', 11);
		
		if($this->dateFrom == '...' || $this->dateTo == '...')
			$this->Cell(0, 0, 'From ... to ...', 0, 0, 'C');
		else if($this->dateFrom == 'month' && $this->dateTo == 'month')
			$this->Cell(0, 0, '', 0, 0, 'C');
		else
		{
			if($this->reportName != 'TALLY')
			{				
				if($this->dateFrom !=  $this->dateTo)
					$this->Cell(0, 0, 'From '. date('M. d, Y',  strtotime($this->dateFrom)) .' to '. date('M d, Y',strtotime($this->dateTo)), 0, 0, 'C');
				else
					$this->Cell(0, 0, 'For '. date('M. d, Y',  strtotime($this->dateFrom)), 0, 0, 'C');	
			}
			
		}
		//$this->SetXY(35,60);
		$this->Ln(10);
		$this->SetFont('helveticaB', '', 11);
		$this->Cell(0, 0, 'Date Printed: '.date('F d, Y'), 0, 0, 'L');
       	$this->SetFont('helvetica', '', 12);
		$this->IncludeJS("print();");
   }
	public function setReportName($str)
	{
		$this->reportName = $str;
	}
	public function setDateFrom($str)
	{
		$this->dateFrom = $str;
	}
	public function setDateTo($str)
	{
		$this->dateTo = $str;
	}
	public function getDateFrom($str)
	{
		return $this->dateFrom;
	}
	public function getDateTo($str)
	{
		return $this->dateTo;
	}
	
	
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        //$this->SetXY(175,-23);
        // Set font
        //$this->SetFont('helvetica', 'I', 12);
        // Page number
       // $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
    }
}