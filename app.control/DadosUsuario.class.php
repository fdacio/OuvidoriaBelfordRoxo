<?php
class DadosUsuario extends Page{
	
	private $html;
	private $mensagem;
	private $mensagem_sucesso;
	private $usuario;

	public function __construct()
	{
		$this->html = file_get_contents('app.view/dadosusuario.html');
		TTransaction::open();
		$this->usuario = new Usuario(TSession::getValue('SISPBR_OUVID_USU_ID'));
		TTransaction::close();
	}

	public function show(){
		if(TSession::getValue('SISPBR_OUVID_USU_ID')){
			parent::run();		
			$this->setDadosForm();
			$this->setMensagemForm();
			echo $this->html;
		}else{
			$login = new Login();
			$login->doLogout();
		}
	}

	public function onEdit(){
		try{			
			$this->setDadosForm();
		}catch(Exception $e){
			$this->mensagem = 'Erro: '. $e->getMessage();
			TTransaction::rollback();
		}
	}			
	
	private function setDadosForm(){
		
		$this->html = str_replace('#ID', $this->usuario->USU_ID, $this->html);
		$this->html = str_replace('#NOME', $this->usuario->USU_NOME, $this->html);
		$this->html = str_replace('#MATRICULA', $this->usuario->USU_MATRICULA, $this->html);
		$this->html = str_replace('#EMAIL', $this->usuario->USU_EMAIL, $this->html);
		$this->html = str_replace('#NIVEL', $this->usuario->NIVEL_USUARIO, $this->html);
		
	}

	private function setMensagemForm(){
		if($this->mensagem_sucesso){
			$this->html = str_replace('#MENSAGEM_SECESSO', $this->mensagem_sucesso, $this->html);
		}else{
			$this->html = str_replace('#MENSAGEM_SECESSO', '', $this->html);
		}
		
		if($this->mensagem){
			$this->html = str_replace('#MSG', $this->mensagem, $this->html);
		}else{
			$this->html = str_replace('#MSG', '', $this->html);
		}

	}


	public function onSave(){

		$this->usuario->USU_NOME = $_POST['nome'];
		$this->usuario->USU_MATRICULA = $_POST['matricula'];

		if($this->usuario->USU_NOME == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#nome_requerido', 'campo_requerido', $this->html);
		}

		if($this->usuario->USU_MATRICULA == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#matricula_requerido', 'campo_requerido', $this->html);
		}
		
		if($this->mensagem == ''){
			
			try	{
				TTransaction::open();
				$this->usuario->store();
				TTransaction::close();
				header('Location:?class=DadosUsuario&method=onSucess');
			}
			catch (Exception $e){
				$this->mensagem = 'Erro ao alterar: '. $e->getMessage();
				TTransaction::rollback();
			}
		}
	}
	
	public function onSucess(){
		$this->mensagem_sucesso = "Dados alterado com sucesso";
	}
	
}