<?php

require_once 'app.report/FPDF.class.php';

class DocPdf extends FPDF
{
	private $logo = 'http://187.58.65.152/moreiatecnologia/docview/documentos/44/44_33009_7195944.jpg';
	private $titleHeader;
	private $user;
	private $datetime;
	private $pageOrientation = 'P';
	private $pageSize = 'A4';
	
	public function __construct()
	{
		parent::__construct($this->pageOrientation,"mm",$this->pageSize);
		
		$this->SetAuthor('Dacio');
		$this->SetCreator('Dacio');
		$this->SetTitle('ReportFPDF');		
		$this->AliasNbPages();
		$this->AddPage();
	}
	
	
   // Page header
	public function setDoc()
	{
		$this->Image($this->logo,0,0,190,270);
	}

	public function download($filename = 'doc.pdf')
	{		
		ob_end_clean();
		$this->Output('D',$filename);
	}
	
}

$doc = new DocPdf();
$doc->setDoc();
$doc->download();

?>