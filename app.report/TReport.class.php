<?php
abstract class TReport extends FPDF
{
	private $logo = 'app.images/logo_ouvidoria_report.png';
	private $logo2 = 'app.images/logo_prefeitura_report.png';
	private $titleHeader;
	private $user;
	private $datetime;
	private $pageOrientation = 'P';
	private $pageSize = 'A4';
	
	public function __construct()
	{
		parent::__construct($this->pageOrientation,"mm",$this->pageSize);
		
		$this->SetAuthor('SISP');
		$this->SetCreator('SISP');
		$this->SetTitle('ReportFPDF');		
		$this->AliasNbPages();
		$this->AddPage();
	}
	
	
	public function setLogo($logo)
	{
		$this->logo = $logo;
	}
	
	public function getLogo()
	{
		return $this->logo;
	}
	
	public function setTitleHeader($titleHeader)
	{
		$this->titleHeader = $titleHeader;
	}
	
	public function getTitleHeader()
	{
		return $this->titleHeader;
	}
	
	public function setUser($user)
	{
		$this->user = $user;
	}
	
	public function getUser()
	{
		return $this->user;
	}
	
	public function setDateTime($datetime)
	{
		$this->datetime = $datetime;
	}
	
	public function getDateTime()
	{
		return $this->datetime;
	}
	
	public function setFilename($filename)
	{
		$this->filename = $filename;
	}
	
	public function getFilename()
	{
		return $this->filename;
	}
	
	public function setPageOrientation($orientation)
	{
		$this->pagOrientation = $orientation;
	}
	
	public function getPageOrientation()
	{
		return $this->pagOrientation;
	}
	
	// Page header
	public function Header()
	{
		
		$this->Rect(10,10,190,30);
		// Logo
		$this->Image($this->logo,12,12);
		$this->Image($this->logo2,172,12);
		// Arial bold 15
		$this->SetFont('Arial','B',16);
		// Move to the right
		
		$this->Cell(190,10,'Ouvidoria Belfort Roxo', 0, 0, 'C');	
		$this->Ln();
		$this->SetFont('Arial','B',14);
		$this->Cell(80);
		// Title
		$this->Cell(30,10,$this->titleHeader, 0, 0, 'C');
		// Line break
		$this->Ln(25);
	}

	
	public abstract function setContent($dataObject);
	
	// Page footer
	public function Footer()
	{
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->Cell(60,10,$this->user,'T',0,'L');
		
		//Datetime
		$this->Cell(70,10,$this->datetime,'T',0,'C');
		
		
		$this->Cell(60,10,'Page '.$this->PageNo().'/{nb}','T',0,'R');
		//User
		
	}
	
	
	public function download($filename = 'report.pdf')
	{		
	    
	    ob_end_clean();		
		$this->Output('D',$filename);
	}
	
}
?>