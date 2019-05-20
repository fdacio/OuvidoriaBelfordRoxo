<?php
class UsuarioReport extends TReport{

	private $usuarios;

	public function __construct()
	{
		parent::setTitleHeader("Relatório de Usuarios");
		TTransaction::open();
		$idUsuario = TSession::getValue('SISPBR_OUVID_USU_ID');
		$usuario = new Usuario($idUsuario);
		TTransaction::close();
		parent::setUser($usuario->USU_NOME);
		parent::setDateTime(date("d/m/Y h:i:s"));		
		parent::__construct();
		
	}

	
	public function setContent($usuarios)
	{

		$this->usuarios = $usuarios;

		$this->SetFont('Arial','B',8);
		$this->Cell(20,5,"Código",'1');
		$this->Cell(60,5,"Nome",'1');
		$this->Cell(60,5,"e-Mail",'1');
		$this->Cell(30,5,"Nível",'1');
		$this->Cell(20,5,"Situação",'1');
		$this->Ln();
		
		$this->SetFont('Arial','',8);
		$count = 0;
		foreach ($this->usuarios as $usuario){
			$this->Cell(20,5,$usuario->USU_ID,'0');
			$this->Cell(60,5,$usuario->USU_NOME,'0');
			$this->Cell(60,5,$usuario->USU_EMAIL,'0');
			$this->Cell(30,5,$usuario->NIVEL_USUARIO,'0');
			$this->Cell(20,5,$usuario->USU_SITUACAO,'0');
			$this->Ln();
			$count++;
		}
		$this->SetFont('Arial','B',8);
		$this->Cell(170,5,"Total de Registros:",'0','0','R');
		$this->Cell(20,5,$count,'0','0','R');
		
	}
}
?>