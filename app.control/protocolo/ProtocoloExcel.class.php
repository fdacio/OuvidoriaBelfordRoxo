<?php
class ProtocoloExcel extends TExcel
{

	private $protocolo;

	public function __construct()
	{
		parent::__construct();		
		$this->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->setActiveSheetIndex(0)->setCellValue('A1', "Relatório de Protocolos" );
	}

	public function setContent($protocolos)	
	{
		
		$this->getActiveSheet()->getStyleByColumnAndRow(0, 2, 20, 2)->getFont()->setBold(true);
		$this->setActiveSheetIndex(0)->setCellValue('A2', 'N°do Protocolo');
		$this->setActiveSheetIndex(0)->setCellValue('B2', 'Data de Abertura');
		$this->setActiveSheetIndex(0)->setCellValue('C2', 'Atendente');
		$this->setActiveSheetIndex(0)->setCellValue('D2', 'Status');
		$this->setActiveSheetIndex(0)->setCellValue('E2', 'Nome');
		$this->setActiveSheetIndex(0)->setCellValue('F2', 'Nascimento');
		
		$this->setActiveSheetIndex(0)->setCellValue('G2', 'Endereço');
		$this->setActiveSheetIndex(0)->setCellValue('H2', 'Bairro');
		$this->setActiveSheetIndex(0)->setCellValue('I2', 'Município');
		$this->setActiveSheetIndex(0)->setCellValue('J2', 'UF');
		$this->setActiveSheetIndex(0)->setCellValue('L2', 'CEP');
		$this->setActiveSheetIndex(0)->setCellValue('M2', 'Celular');
		$this->setActiveSheetIndex(0)->setCellValue('N2', 'Telefone');
		$this->setActiveSheetIndex(0)->setCellValue('O2', 'Email');
		$this->setActiveSheetIndex(0)->setCellValue('P2', 'Rede Social');
		$this->setActiveSheetIndex(0)->setCellValue('Q2', 'Destino');		
		$this->setActiveSheetIndex(0)->setCellValue('R2', 'Pedido/Reclamação/Sugestão');
		$this->setActiveSheetIndex(0)->setCellValue('S2', 'Observação');
		
		$this->setActiveSheetIndex(0)->setCellValue('T2', 'Data Finalização');
		$this->setActiveSheetIndex(0)->setCellValue('U2', 'Atendente');
		$this->setActiveSheetIndex(0)->setCellValue('V2', 'Resultado');
		
		
		$line = 3;
		foreach($protocolos as $this->protocolo){
			$this->setActiveSheetIndex(0)->setCellValue('A'.$line, $this->protocolo->PRO_NUMERO_ANO);
			$this->setActiveSheetIndex(0)->setCellValue('B'.$line, $this->protocolo->DATA);
			$this->setActiveSheetIndex(0)->setCellValue('C'.$line, $this->protocolo->ATENDENTE);
			$this->setActiveSheetIndex(0)->setCellValue('D'.$line, $this->protocolo->STATUS);
			$this->setActiveSheetIndex(0)->setCellValue('E'.$line, $this->protocolo->PRO_NOME);
			$this->setActiveSheetIndex(0)->setCellValue('F'.$line, $this->protocolo->NASCIMENTO);
			$this->setActiveSheetIndex(0)->setCellValue('G'.$line, $this->protocolo->PRO_ENDERECO);
			$this->setActiveSheetIndex(0)->setCellValue('H'.$line, $this->protocolo->PRO_BAIRRO);
			$this->setActiveSheetIndex(0)->setCellValue('I'.$line, $this->protocolo->PRO_MUNICIPIO);
			$this->setActiveSheetIndex(0)->setCellValue('J'.$line, $this->protocolo->PRO_UF);
			$this->setActiveSheetIndex(0)->setCellValue('L'.$line, $this->protocolo->PRO_CEP);
			$this->setActiveSheetIndex(0)->setCellValue('M'.$line, $this->protocolo->PRO_CELULAR);
			$this->setActiveSheetIndex(0)->setCellValue('N'.$line, $this->protocolo->PRO_TELEFONE);
			$this->setActiveSheetIndex(0)->setCellValue('O'.$line, $this->protocolo->PRO_EMAIL);
			$this->setActiveSheetIndex(0)->setCellValue('P'.$line, $this->protocolo->PRO_REDESOCIAL);
			$this->setActiveSheetIndex(0)->setCellValue('Q'.$line, $this->protocolo->PRO_DESTINO);
			$this->setActiveSheetIndex(0)->setCellValue('R'.$line, $this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO);
			$this->setActiveSheetIndex(0)->setCellValue('S'.$line, $this->protocolo->PRO_OBSERVACAO);
			$this->setActiveSheetIndex(0)->setCellValue('T'.$line, $this->protocolo->DATA_FINALIZACAO);
			$this->setActiveSheetIndex(0)->setCellValue('U'.$line, $this->protocolo->ATEND_RESULTADO);
			$this->setActiveSheetIndex(0)->setCellValue('V'.$line, $this->protocolo->PRO_RESULTADO);
			$line++;
		}
		
		
	}
}
?>