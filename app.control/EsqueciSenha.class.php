<?php

class EsqueciSenha extends Page{
	

	private $html;
	private $mensagem;
	private $mensagem_sucesso;
	private $email;
	private $confirmaremail;

	public function __construct(){
		$this->html = file_get_contents('app.view/esquecisenha.html');
	}

	public function show(){
		parent::run();
		$this->setDadosForm();
		$this->setMensagemForm();
		echo $this->html;
	}

	private function setDadosForm(){
		$this->html = str_replace('#EMAIL', $this->email, $this->html);
		$this->html = str_replace('#CONFIRMAREMAIL', $this->confirmaremail, $this->html);
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
	
	
	public function goEnviar(){
		
		$this->email = $_POST['email'];
		$this->confirmaremail = $_POST['confirmaremail'];
		
		if($this->email == ''){
			$this->mensagem = "Informe os campos obrigatórios";
			$this->html = str_replace('#email_requerido', 'campo_requerido', $this->html);
		}
		
		if($this->confirmaremail == ''){
			$this->mensagem = "Informe os campos obrigatórios";
			$this->html = str_replace('#confirmaremail_requerido', 'campo_requerido', $this->html);
		}
		
		if($this->email != $this->confirmaremail){
			$this->mensagem = "Confirmação de e-Mail inválida";
		}
		
		if($this->mensagem == ''){
				
				$criteria = new TCriteria;
				$filter = new TFilter('USU_EMAIL', '=', $this->email);
				$criteria->add($filter);				
				TTransaction::open();
				$repository = new TRepository('Usuario');
				$usuarios = $repository->load($criteria);
				TTransaction::close();
				
				if(count($usuarios)> 0){
					$senhaRandomica = mt_rand(100000,999999);
					$senhaMd5 = TFuncoes::passwordMD5($senhaRandomica);
					
					
					$from_name = "SISP - Sistema Integrado de Suporte ao Patrimônio ";
					$from_email = "sispbr@sispbr.com.br";
					$to_email = $this->email;
					
					$subject = "SISP - Nova senha de acesso ao sistema";

					$message = "<h2>Novo dados de acesso ao sistema</h2>";
					$message .="<span>e-Mail: </span><b>".$this->email."</b><br />";
					$message .="<span>Senha: </span><b>".$senhaRandomica."</b><br />";
					
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: SISP <sispbr@sispbr.com.br>' . "\r\n";
					
							
					try	{
						
						TTransaction::open();
						$usuario = $usuarios[0];
						$id = $usuario->USU_ID;
						$usuario = new Usuario($id);
						$usuario->USU_SENHA = $senhaMd5;
						$usuario->store();
						TTransaction::close();
						//Envio do email
						if(mail($to_email, $subject, $message, $headers)){
							$this->mensagem_sucesso = 'Uma nova senha foi enviada pro seu e-mail.';
							$this->email = '';
							$this->confirmaremail = '';
						}else{
							$this->mensagem = 'Erro ao enviar a mensagem';
						}
						
					}
					catch (Exception $e){
						$this->mensagem = 'Erro ao registrar nova senha: '. $e->getMessage(). "<br />";
						TTransaction::rollback();
					}
							
					
					
				}else{
					$this->mensagem = "e-Mail não cadastrado no sistema";
				}
		
		}
		
	}
	
}

?>

