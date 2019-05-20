<?php

class Cadastrese extends Page{

	private $html;
	private $mensagem;
	private $usuario;

	public function __construct(){
		$this->html = file_get_contents('app.view/cadastrese.html');
		$this->usuario = new Usuario();
	}

	public function show(){
		parent::run();
		$this->setDadosForm();
		$this->setMensagemForm();
		echo $this->html;
	}

	private function setDadosForm(){
		$this->html = str_replace('#NOME', $this->usuario->USU_NOME, $this->html);
		$this->html = str_replace('#EMAIL', $this->usuario->USU_EMAIL, $this->html);
		$this->html = str_replace('#MATRICULA', $this->usuario->USU_MATRICULA, $this->html);
	}

	private function setMensagemForm(){
		if($this->mensagem){
			$this->html = str_replace('#MSG', $this->mensagem, $this->html);
		}else{
			$this->html = str_replace('#MSG', '', $this->html);
		}

	}


	public function onSave(){

		$this->usuario->USU_NOME = $_POST['nome'];
		$this->usuario->USU_EMAIL = $_POST['email'];
		$this->usuario->USU_MATRICULA = $_POST['matricula'];
		$this->usuario->USU_SENHA = $_POST['senha'];
		$confirmaSenha = $_POST['confirmar_senha'];

		if($this->usuario->USU_NOME == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#nome_requerido', 'campo_requerido', $this->html);
		}

		if($this->usuario->USU_EMAIL == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#email_requerido', 'campo_requerido', $this->html);
		}
		
		if($this->usuario->USU_MATRICULA == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#matricula_requerido', 'campo_requerido', $this->html);
		}
		
		if($this->usuario->USU_SENHA == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#senha_requerido', 'campo_requerido', $this->html);
		}

		if($confirmaSenha == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#confirmar_senha_requerido', 'campo_requerido', $this->html);
		}
		

		if(!(TFuncoes::validaEmail($this->usuario->USU_EMAIL))){
				$this->mensagem .= "e-Mail inválido <br />";
				$this->html = str_replace('#email_requerido', 'campo_requerido', $this->html);
		}
		
		if($confirmaSenha != $this->usuario->USU_SENHA){
			$this->mensagem .= "Confirmação de senha inválida <br />";
			$this->html = str_replace('#confirma_senha_requerido', 'campo_requerido', $this->html);
		}

		if($this->usuario->USU_EMAIL != ''){
				$criteria = new TCriteria;
				$filter = new TFilter('USU_EMAIL', '=', $this->usuario->USU_EMAIL);
				$criteria->add($filter);
					
				TTransaction::open();
				$repository = new TRepository('Usuario');
				$usuarios = $repository->load($criteria);
				TTransaction::close();
	
				if(count($usuarios)>0){
					$this->mensagem .= 'e-Mail já cadastrado no sistema';
				}
		}

		if($this->mensagem == ''){
			$this->usuario->USU_NIVEL = 2;
			$this->usuario->USU_ATIVO = 0;
			$this->usuario->USU_SENHA = TFuncoes::passwordMD5($this->usuario->USU_SENHA);
			
			try	{
			
				TTransaction::open();
				$this->usuario->store();
				TTransaction::close();
				TSession::setValue('SISPBR_OUVID_USU_EMAIL_SUCESSO',$this->usuario->USU_EMAIL);
				header('Location:?class=Login&method=onCadastroSucesso');
				
			}
			catch (Exception $e){
				$this->mensagem = 'Erro ao cadastrar: '. $e->getMessage();
				TTransaction::rollback();
			}
		}
	}
}

?>