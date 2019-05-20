<?php
class UsuarioFilterReportForm extends Page{

	private $html;
	private $mensagem;
	private $usuario;


	public function __construct(){
		$this->html = file_get_contents('app.view/usuariofilterreportform.html');
		$this->usuario = new Usuario();
	}

	public function show(){
		if(TSession::getValue('SISPBR_OUVID_USU_ID')){
			parent::run();
			$this->setDadosForm();
			$this->setMensagemForm();
			echo $this->html;
		}else{
			header('Location:?class=Login');
		}
	}

	private function setDadosForm(){

		if($this->usuario->ORDERBY == 'USU_NOME')
		{
			$this->html = str_replace('title="#CHECKED_CODIGO"', "", $this->html);
			$this->html = str_replace('title="#CHECKED_NOME"', "checked='checked'", $this->html);
		}
		else
		{
			$this->html = str_replace('title="#CHECKED_CODIGO"', "checked='checked'", $this->html);
			$this->html = str_replace('title="#CHECKED_NOME"', "", $this->html);
		}
		
		
		$nivelUsuario = new NivelUsuario();
		$niveis = $nivelUsuario->getNiveis();
		$options = "";
		foreach ($niveis as $key=>$value)
		{
			if($this->usuario->USU_NIVEL == $value){
				$selected = "selected='selected'";
			}else{
				$selected = "";
			}
			$options .="<option value=".$key." ".$selected." >".$value."</option>";
		}
		$this->html = str_replace('<option>#OPTIONS_NIVEL</option>', $options, $this->html);

		$situacoes = $this->usuario->arraySituacao;
		$options = "";
		foreach ($situacoes as $key=>$value)
		{
			if($this->usuario->USU_ATIVO == $value){
				$selected = "selected='selected'";
			}else{
				$selected = "";
			}
			$options .="<option value=".$key." ".$selected." >".$value."</option>";
		}
		$this->html = str_replace('<option>#OPTIONS_SITUACAO</option>', $options, $this->html);
		
	
	}

	private function setMensagemForm(){

		if($this->mensagem){
			$this->html = str_replace('#MSG', $this->mensagem, $this->html);
		}else{
			$this->html = str_replace('#MSG', '', $this->html);
		}

	}

	public function onGerarPdf(){

		ob_end_clean();
		$this->usuario->ORDERBY = $_POST['order_by'];
		$this->usuario->USU_NIVEL = $_POST['nivel'];
		$this->usuario->USU_ATIVO = $_POST['situacao'];
		


		try{
			TTransaction::open();
			$criteria = new TCriteria();
			if($this->usuario->USU_NIVEL > 0)
			{
				$criteria->add(new TFilter('USU_NIVEL', '=', $this->usuario->USU_NIVEL));
			}
			
			if($this->usuario->USU_ATIVO > -1)
			{
				$criteria->add(new TFilter('USU_ATIVO', '=', $this->usuario->USU_ATIVO));
			}
			$criteria->setProperty('order', $this->usuario->ORDERBY);
			$repository = new TRepository('Usuario');
			$usuarios = $repository->load($criteria);
			TTransaction::close();

			$report = new UsuarioReport();
			$report->setContent($usuarios);
			$report->download('ReportUsuario.pdf');
		}catch (Exception $e){
			new TMessage('Erro ao gerar pdf: '. $e->getMessage(), NULL," app.images/error.png");
			TTransaction::rollback();
		}




	}

}