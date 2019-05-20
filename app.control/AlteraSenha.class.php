<?php
class AlteraSenha extends Page{
	
	private $html;
	private $mensagem;
	private $mensagem_sucesso;
	private $usuario;
	private $senha;
	private $confirmasenha;

	public function __construct(){
		$this->html = file_get_contents('app.view/alterasenha.html');
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

	private function setObjects(){
		TTransaction::open();
		$this->usuario = new Usuario(TSession::getValue('SISPBR_OUVID_USU_ID'));
		TTransaction::close();
	}

	public function onEdit(){
		$this->setObjects();
	}			
	
	private function setDadosForm(){
		
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
		
		$this->senha = $_POST['senha'];
		$this->confirmasenha = $_POST['confirmar_senha'];
		
		if($this->senha == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#senha_requerido', 'campo_requerido', $this->html);
		}

		if($this->confirmasenha != $this->senha){
			$this->mensagem .= "Confirmação de senha inválida <br />";
			$this->html = str_replace('#confirma_senha_requerido', 'campo_requerido', $this->html);
		}
		
		
		if($this->mensagem == ''){
			
			try	{
				$this->setObjects();
				TTransaction::open();
				$this->usuario->USU_SENHA = TFuncoes::passwordMD5($this->senha);
				$this->usuario->store();
				TTransaction::close();
				header('Location:?class=AlteraSenha&method=onSucess');
			}
			catch (Exception $e){
				$this->mensagem = 'Erro ao alterar: '. $e->getMessage();
				TTransaction::rollback();
			}
		}
	}
	
	public function onSucess(){
		$this->mensagem_sucesso = "Senha alterada com sucesso";
	}
	
}