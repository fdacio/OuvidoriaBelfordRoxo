<?php
class ProtocoloReport extends TReport{

	private $protocolo;

	public function __construct()
	{
		parent::setTitleHeader("Relatório de Protocolos");
		TTransaction::open();
		$idUsuario = TSession::getValue('SISPBR_OUVID_USU_ID');
		$usuario = new Usuario($idUsuario);
		TTransaction::close();
		parent::setUser($usuario->USU_NOME);
		parent::setDateTime(date("d/m/Y h:i:s"));

		parent::__construct();
	}

	public function setContent($protocolos)
	{

		$count = 0;
		foreach($protocolos as $this->protocolo){

			$this->SetFont('Arial','B',10);
			$this->Cell(30,5,"N° do Protocolo",'TRL');
			$this->SetX(40);
			$this->Cell(50,5,"Data de Abertura",'TRL');
			$this->SetX(90);
			$this->Cell(60,5,"Atendente",'TRL');
			$this->SetX(150);
			$this->Cell(50,5,"Status",'TRL');
			$this->Ln();

			$this->SetFont('Arial','',10);
			$this->Cell(30,5,$this->protocolo->PRO_NUMERO_ANO,'RBL');
			$this->SetX(40);
			$this->Cell(50,5,$this->protocolo->DATA,'RBL');
			$this->SetX(90);
			$this->Cell(60,5,TFuncoes::resume($this->protocolo->ATENDENTE, 25),'RBL');
			$this->SetX(150);
			$this->Cell(50,5,$this->protocolo->STATUS,'RBL');
			$this->Ln();

			$this->SetFont('Arial','B',10);
			$this->Cell(150,5,"Nome",'TRL');
			$this->SetX(160);
			$this->Cell(40,5,"Nascimento",'TRL');
			$this->Ln();
				
			$this->SetFont('Arial','',10);
			$this->Cell(150,5,$this->protocolo->PRO_NOME,'RBL');
			$this->SetX(160);
			$this->Cell(40,5,$this->protocolo->NASCIMENTO,'RBL');
			$this->Ln();
				
			$this->SetFont('Arial','B',10);
			$this->Cell(120,5,"Endereço",'LR');
			$this->SetX(130);
			$this->Cell(70,5,"Bairro",'LR');
			$this->Ln();
			$this->SetFont('Arial','',10);
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
			$this->SetFont('Arial','',10);
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
			$this->SetFont('Arial','',10);
			$this->Cell(95,5,$this->protocolo->PRO_CELULAR,'LRB');
			$this->SetX(105);
			$this->Cell(95,5,$this->protocolo->PRO_TELEFONE,'LRB');
			$this->Ln();

			$this->SetFont('Arial','B',10);
			$this->Cell(95,5,"Email",'LR');
			$this->SetX(105);
			$this->Cell(95,5,"Rede Social",'LR');
			$this->Ln();
			$this->SetFont('Arial','',10);
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
			$this->Cell(190,5,"Pedido/Reclamação/Sugestão",'TRL');
			$this->Ln();
			$this->SetFont('Arial','',10);
			$this->MultiCell(190,5,($this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO),'LRB');
				
			$this->SetFont('Arial','B',10);
			$this->Cell(190,5,"Observação",'TRL');
			$this->Ln();
			$this->SetFont('Arial','',10);
			$this->MultiCell(190,5,($this->protocolo->PRO_OBSERVACAO),'LRB');
				
			if($this->protocolo->PRO_STATUS == 3){
				$this->SetFont('Arial','B',10);
				$this->Cell(60,5,"Data Finalização",'LR');
				$this->SetX(70);
				$this->Cell(130,5,"Atendente",'LR');
				$this->Ln();
				$this->SetFont('Arial','',10);
				$this->Cell(60,5,$this->protocolo->DATA_FINALIZACAO,'LRB');
				$this->SetX(70);
				$this->Cell(130,5,$this->protocolo->ATEND_RESULTADO,'LRB');

				$this->Ln();
				$this->SetFont('Arial','B',10);
				$this->Cell(190,5,"Resultado",'LR');
				$this->Ln();
				$this->SetFont('Arial','',10);
				$this->MultiCell(190,5,($this->protocolo->PRO_RESULTADO),'LRB');
			}
			$count++;
			$this->Ln(5);
		}

		$this->SetFont('Arial','B',10);
		$this->Cell(170,5,"Total de Registros:",'0','0','R');
		$this->Cell(20,5,$count,'0','0','R');

	}
}
?>