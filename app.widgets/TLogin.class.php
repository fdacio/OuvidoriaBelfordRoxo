<?php
class TLogin {
	private $class = 'Usuarios'; 
	private $email;
	private $pass;
	private $mensagem;

	
	public function setEmail($email){
		$this->email = $email;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function setPass($pass){
		$this->pass = md5($pass);
	}
	
	public function getMensagem(){
		return $this->mensagem;
	}
	
	public function onLogin(){
		$this->setEmail($_POST['email']);
		$this->setPass($_POST['senha']);
		
    	$criteria = new TCriteria;
		$filter = new TFilter('USU_EMAIL', '=', $this->email);
		$criteria->add($filter);
		
		$filter = new TFilter('USU_SENHA', '=',$this->pass);
		$criteria->add($filter);
		
		TTransaction::open();
		$repository = new TRepository($this->class);
		$usuarios = $repository->load($criteria);
		
		
		if(count($usuarios)==0)
		{
			$this->mensagem = 'e-Mail ou Senha inválidos';
			return false;
		}
		else
		{
			$usuario = $usuarios[0];
			TSession::setValue('SISPBR_USU_ID',$usuario->USU_ID);
			TSession::setValue('SISPBR_USU_NIVEL',$usuario->USU_NIVEL);
			header('Location:.');
			/*
			if($usuario->USU_NIVEL == 3){
				header('Location:?class=MenuCliente'); 
			}else{
				header('Location:?class=MenuAdmin'); 
			}
			*/
			
			
		}
	}
	
	/**
	 * 
	 * Método para efetuar o logout do sistema
	 * Sai do sistema e excluir a seção atual
	 */
	public function onLogout(){
				
		TSession::freeSession();
		header('Location:?class=Index');//encaminha para index php
	}
	
	
	
	public function getUserId($email)
	{
    	$criteria = new TCriteria;
		$filter = new TFilter('USU_EMAIL', '=', $email);
		$criteria->add($filter);
		TTransaction::open();
		$repository = new TRepository($this->class);
		$usuarios = $repository->load($criteria);
		
		if(count($usuarios)>0)
		{
			$usuario = $usuarios[0];
			return $usuario->USU_ID;
		}
		else
		{
			return 0;
		}
	}
	
	public function getUserNivel($usu_id)
	{
    	$criteria = new TCriteria;
		$filter = new TFilter('USU_ID', '=', $usu_id);
		$criteria->add($filter);
		TTransaction::open();
		$repository = new TRepository($this->class);
		$usuarios = $repository->load($criteria);
		
		if(count($usuarios)>0)
		{
			$usuario = $usuarios[0];
			return $usuario->USU_NIVEL;
		}
		else
		{
			return 0;
		}
	}
	
	public function getUserEmail($usu_id)
	{
    	$criteria = new TCriteria;
		$filter = new TFilter('USU_ID', '=', $usu_id);
		$criteria->add($filter);
		TTransaction::open();
		$repository = new TRepository($this->class);
		$usuarios = $repository->load($criteria);
		
		if(count($usuarios)>0)
		{
			$usuario = $usuarios[0];
			return $usuario->USU_EMAIL;
		}
		else
		{
			return 0;
		}
	}
	
	public function getUserName($usu_id)
	{
    	$criteria = new TCriteria;
		$filter = new TFilter('USU_ID', '=', $usu_id);
		$criteria->add($filter);
		TTransaction::open();
		$repository = new TRepository($this->class);
		$usuarios = $repository->load($criteria);
		
		if(count($usuarios)>0)
		{
			$usuario = $usuarios[0];
			return $usuario->USU_NOME;
		}
		else
		{
			return 0;
		}
	}
	
	
}
?>