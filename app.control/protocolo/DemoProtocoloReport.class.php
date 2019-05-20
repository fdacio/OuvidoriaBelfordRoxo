<?php
class DemoProtocoloReport extends TReport{

	private $protocolo;

	public function __construct()
	{
		parent::setTitleHeader("PROTOCOLO");
		TTransaction::open();
		$idUsuario = TSession::getValue('SISPBR_OUVID_USU_ID');
		$usuario = new Usuario($idUsuario);
		TTransaction::close();
		parent::setUser($usuario->USU_NOME);
		parent::setDateTime(date("d/m/Y h:i:s"));
		
		parent::__construct();
		
	}

	public function setContent($protocolo){

		$this->protocolo = $protocolo;

		$this->SetFont('Arial','B',12);
		$this->Cell(190,10,"Dados do Protocolo",'1','0','C');
		$this->Ln();
		
		$this->SetFont('Arial','B',10);
		$this->Cell(35,5,"Número",'LR');
		$this->SetX(45);
		$this->Cell(50,5,"Data do Atendimento",'LR');
		$this->SetX(95);
		$this->Cell(70,5,"Atendente",'LR');
		$this->SetX(165);
		$this->Cell(35,5,"Status",'LR');
		$this->Ln();

		$this->SetFont('Arial','',12);
		$this->Cell(35,5,$this->protocolo->PRO_NUMERO_ANO,'LRB');
		$this->SetX(45);
		$this->Cell(50,5,$this->protocolo->DATA,'LRB');
		$this->SetX(95);
		$this->Cell(70,5,TFuncoes::resume($this->protocolo->ATENDENTE, 25),'LRB');
		$this->SetX(165);
		$this->Cell(35,5,$this->protocolo->STATUS,'LRB');
		$this->Ln();

		$this->SetFont('Arial','B',10);
		$this->Cell(140,5,"Nome",'LR');
		$this->SetX(150);
		$this->Cell(50,5,"Nascimento",'LR');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(140,5,$this->protocolo->PRO_NOME,'LRB');
		$this->SetX(150);
		$this->Cell(50,5,$this->protocolo->NASCIMENTO,'LRB');
		$this->Ln();
		
		$this->SetFont('Arial','B',10);
		$this->Cell(120,5,"Endereço",'LR');
		$this->SetX(130);
		$this->Cell(70,5,"Bairro",'LR');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(120,5,$this->protocolo->PRO_ENDERECO,'LRB');
		$this->SetX(130);
		$this->Cell(70,5,$this->protocolo->PRO_BAIRRO,'LRB');
		$this->Ln();

		
		$this->SetFont('Arial','B',10);
		$this->Cell(100,5,"Município",'LR');
		$this->SetX(110);
		$this->Cell(40,5,"UF",'LR');
		$this->SetX(150);
		$this->Cell(50,5,"CEP",'LR');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(100,5,$this->protocolo->PRO_MUNICIPIO,'LRB');
		$this->SetX(110);
		$this->Cell(40,5,$this->protocolo->PRO_UF,'LRB');
		$this->SetX(150);
		$this->Cell(50,5,$this->protocolo->PRO_CEP,'LRB');
		$this->Ln();
		
		$this->SetFont('Arial','B',10);
		$this->Cell(95,5,"Celular",'LR');
		$this->SetX(105);
		$this->Cell(95,5,"Telefone",'LR');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(95,5,$this->protocolo->PRO_CELULAR,'LRB');
		$this->SetX(105);
		$this->Cell(95,5,$this->protocolo->PRO_TELEFONE,'LRB');
		$this->Ln();
		
		$this->SetFont('Arial','B',10);
		$this->Cell(95,5,"Email",'LR');
		$this->SetX(105);
		$this->Cell(95,5,"Rede Social",'LR');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(95,5,$this->protocolo->PRO_EMAIL,'LRB');
		$this->SetX(105);
		$this->Cell(95,5,$this->protocolo->PRO_REDESOCIAL,'LRB');
		$this->Ln();
		
		$this->SetFont('Arial','B',10);
		$this->Cell(190,5,"Destino",'LR');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(190,5,($this->protocolo->PRO_DESTINO),'LRB');
		$this->Ln();
		
		$this->SetFont('Arial','B',10);
		$this->Cell(190,5,"Pedido/Reclamação/Sugestão",'LR');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->MultiCell(190,5,($this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO),'LRB');
		
		$this->SetFont('Arial','B',10);
		$this->Cell(190,5,"Observação",'LR');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->MultiCell(190,5,($this->protocolo->PRO_OBSERVACAO),'LRB');
				
		
		$this->SetFont('Arial','B',10);
		$this->Cell(95,5,"Responsável Pelo Atendimento",'LR');
		$this->SetX(105);
		$this->Cell(95,5,"Matrícula",'LR');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(95,5,$this->protocolo->ATENDENTE,'LRB');
		$this->SetX(105);
		$this->Cell(95,5,$this->protocolo->ATENDENTE_MATRICULA,'LRB');
		$this->Ln();
	
		if($this->protocolo->PRO_STATUS == 2)
		{
			$this->SetFont('Arial','B',10);
			$this->Cell(190,5,"Data de Execução",'LTR');
			$this->Ln();
			$this->SetFont('Arial','',12);
			$this->MultiCell(190,5,($this->protocolo->DATA_EXECUCAO),'LRB');
		}
		
		if($this->protocolo->PRO_STATUS == 3)
		{
			$this->SetFont('Arial','B',10);
			$this->Cell(190,5,"Resultado",'LTR');
			$this->Ln();
			$this->SetFont('Arial','',12);
			$this->MultiCell(190,5,($this->protocolo->PRO_RESULTADO),'LRB');
			
			$this->SetFont('Arial','B',10);
			$this->Cell(90,5,"Responsável Pela Execução",'LR');
			$this->SetX(100);
			$this->Cell(50,5,"Matrícula",'LR');
			$this->SetX(150);
			$this->Cell(50,5,"Data da Execução",'LR');
			$this->Ln();
			$this->SetFont('Arial','',12);
			$this->Cell(90,5,$this->protocolo->ATEND_RESULTADO,'LRB');
			$this->SetX(100);
			$this->Cell(50,5,$this->protocolo->ATEND_RESULTADO_MATRICULA,'LRB');
			$this->SetX(150);
			$this->Cell(50,5,$this->protocolo->DATA_FINALIZACAO,'LRB');

		}
		$this->Ln();
		$this->Ln();
		
		$this->Cell(190,5,"-------------------------------------------------------------------------------------------------------------------------------------",'LR');
		$this->Ln(10);
		
		
		$logo = 'app.images/logo_ouvidoria_report.png';
		$logo2 = 'app.images/logo_prefeitura_report.png';
		
		$this->Rect($this->GetX(),$this->GetY(),30,25);
		$this->Image($logo,$this->GetX()+1,$this->GetY()+1,28,18);
		$this->Rect($this->GetX()+170,$this->GetY(),20,25);
		$this->Image($logo2,($this->GetX()+171),$this->GetY()+2,18,18);
		
		$this->SetX(40);
		$this->SetFont('Arial','B',8);
		$this->Cell(35,5,'Protocolo','T');
		$this->SetX(75);
		$this->Cell(105,5,'Data do Atendimento','T');
		$this->Ln();
		$this->SetX(40);
		$this->SetFont('Arial','',10);
		$this->Cell(35,5,$this->protocolo->PRO_NUMERO_ANO);
		$this->SetX(75);
		$this->Cell(105,5,$this->protocolo->DATA);
		$this->Ln();
		$this->SetX(40);
		$this->Cell(140,5,'_________________________________________________','',0,'C');
		$this->Ln();
		$this->SetX(40);
		$this->Cell(140,5,$this->protocolo->ATENDENTE,'',0,'C');
		$this->Ln();
		$this->SetX(40);
		$this->Cell(140,5,"Matrícula:" . $this->protocolo->ATENDENTE_MATRICULA,'B',0,'C');
		
	}
}
?>