<?php
class ExcluirProtocoloForm extends Page{
	private $html;
	private $mensagem;
	private $protocolo;

	public function __construct(){
		$this->html = file_get_contents('app.view/protocoloexcluir.html');
		$this->protocolo = new Protocolo();
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

	public function onEdit()
	{
		$this->setObjetcs();

	}

	public function onDelete()
	{
		$this->setObjetcs();
		$this->mensagem = "Deseja excluir este protocolo? <br />";

	}

	private function setObjetcs()
	{
		$idProtocolo = isset($_GET['key'])?$_GET['key']:'';
		if($idProtocolo != '')
		{
			$niveUsuario = TSession::getValue('SISPBR_OUVID_USU_NIVEL');
			if($niveUsuario == '1')
			{
				TTransaction::open();
				$this->protocolo = new Protocolo($idProtocolo);
				TTransaction::close();
			}
			else 
			{
				$action1 = new TAction(array($this, 'goList'));
				new TMessage("Operação permitida somente para administrador.",$action1," app.images/info.png");
				
			}
		}

	}


	private function setDadosForm(){

		$this->html = str_replace('#PRO_ID',$this->protocolo->PRO_ID, $this->html);
		$this->html = str_replace('#PRO_NUMERO_ANO', $this->protocolo->PRO_NUMERO_ANO, $this->html);
		$this->html = str_replace('#PRO_NOME', $this->protocolo->PRO_NOME, $this->html);
		$this->html = str_replace('#PRO_PEDIDO_RECLAMACAO_SUGESTAO', ($this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO), $this->html);
	}

	private function setMensagemForm()
	{
		if($this->mensagem){
			$this->html = str_replace('#MSG', $this->mensagem, $this->html);
		}else{
			$this->html = str_replace('#MSG', '', $this->html);
		}

	}


	public function Delete()
	{

		$this->setObjetcs();

		$this->protocolo->PRO_ID = $_POST['pro_id'];
		$this->protocolo->PRO_STATUS = 4;
		try	{
				
			TTransaction::open();
			$this->protocolo->store();
			TTransaction::close();

			$action1 = new TAction(array(new ProtocoloAbertoList(), 'onReload'));

			new TMessage("Protocolo excluido com sucesso!",$action1," app.images/info.png");



		}
		catch (Exception $e){
			$this->mensagem = 'Erro ao excluir protocolo: '. $e->getMessage();
			TTransaction::rollback();
		}
	}
	
	function goList()
	{
		$classList = "ProtocoloAbertoList";
		$objList = new $classList;
		$action = new TAction(array($objList,'onReload'));
		$action->setParameter('pag',$_GET['pag']);
		$url = $action->serialize();
		header("Location: $url");
	}
	
}
?>