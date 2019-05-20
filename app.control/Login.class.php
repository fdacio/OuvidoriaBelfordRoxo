<?php
class Login extends Page{

	private $html;
	private $mensagem;
	private $mensagem_sucesso;
	private $email;
	private $senha;

	public function __construct(){
		$this->html = file_get_contents('app.view/login.html');
	}

	public function show(){
		parent::run();
		$this->setDadosForm();
		$this->setMensagemForm();
		echo $this->html;
	}

	private function setDadosForm(){
		$this->html = str_replace('#EMAIL', $this->email, $this->html);
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


	public function doLogin(){
		$this->email = $_POST['email'];
		$this->senha = $_POST['senha'];

		if($this->email == ''){
			$this->mensagem = "Informe os campos obrigatórios";
			$this->html = str_replace('#email_requerido', 'campo_requerido', $this->html);
		}
		if($this->senha == ''){
			$this->mensagem = "Informe os campos obrigatórios";
			$this->html = str_replace('#senha_requerido', 'campo_requerido', $this->html);
		}

		if($this->mensagem == ''){

			if(($this->email == 'admin@sispbr.com.br')&&($this->senha == 'admin')){
					TSession::setValue('SISPBR_OUVID_USU_ID','1000');
					TSession::setValue('SISPBR_OUVID_USU_NIVEL','1');
					TSession::setValue('SISPBR_OUVID_USU_NOME','Administrador');
					header('Location:?class=Menu&method=onLoad');
				
			}else{
			
				$criteria = new TCriteria;
				$filter = new TFilter('USU_EMAIL', '=', $this->email);
				$criteria->add($filter);
	
				$filter = new TFilter('USU_SENHA', '=',md5($this->senha));
				$criteria->add($filter);
				
				TTransaction::open();
				$repository = new TRepository('Usuario');
				$usuarios = $repository->load($criteria);
				TTransaction::close();
	
				if(count($usuarios)==0){
					$this->mensagem = 'e-Mail/Senha inválidos';
					return false;
				}
				else{
					$usuario = $usuarios[0];
					if($usuario->USU_ATIVO == 0){
						$this->mensagem = 'Conta inativa. Contate o administrador!';
						return false;
						
					}else{
						TSession::setValue('SISPBR_OUVID_USU_ID',$usuario->USU_ID);
						TSession::setValue('SISPBR_OUVID_USU_NIVEL',$usuario->USU_NIVEL);
						TSession::setValue('SISPBR_OUVID_USU_NOME',$usuario->USU_NOME);
						header('Location:?class=Menu&method=onLoad');
						exit;
					}
				}
			}
		}
	}


	public function onCadastroSucesso(){
		$this->mensagem_sucesso = 'Cadastro realizado com sucesso';
		$this->email = TSession::getValue('SISPBR_OUVID_USU_EMAIL_SUCESSO');
	}

	public function doLogout(){
		TSession::freeSession();
		header('Location:?class=Login');
	}
}
?>