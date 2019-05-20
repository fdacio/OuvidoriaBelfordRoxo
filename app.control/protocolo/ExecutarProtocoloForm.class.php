<?php
class ExecutarProtocoloForm extends Page{
	private $html;
	private $mensagem;
	private $protocolo;

	public function __construct(){
		$this->html = file_get_contents('app.view/protocoloexecutar.html');
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

	public function onExecutar()
	{
		$this->setObjetcs();
		
	}

	private function setObjetcs()
	{
		$idProtocolo = isset($_GET['key'])?$_GET['key']:'';
		if($idProtocolo != '')
		{
			TTransaction::open();
			$this->protocolo = new Protocolo($idProtocolo);
			TTransaction::close();
		}

	}


	private function setDadosForm(){

		$this->html = str_replace('#PRO_ID',$this->protocolo->PRO_ID, $this->html);
		$this->html = str_replace('#PRO_NUMERO_ANO', $this->protocolo->PRO_NUMERO_ANO, $this->html);
		$this->html = str_replace('#PRO_NUMERO',$this->protocolo->PRO_NUMERO, $this->html);
		$this->html = str_replace('#PRO_ANO',$this->protocolo->PRO_ANO, $this->html);

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


	public function Executar()
	{

		//$this->setObjetcs();

		$this->protocolo->PRO_ID = $_POST['pro_id'];
		$this->protocolo->PRO_STATUS = 2;
		$this->protocolo->PRO_DATA_EXECUCAO = date("Y-m-d H:i:s");
		try	{
				
			TTransaction::open();
			$this->protocolo->store();
			TTransaction::close();

			$action1 = new TAction(array(new ProtocoloAbertoList(), 'onReload'));

			new TMessage("Protocolo colocado em execução com sucesso!",$action1," app.images/info.png");

		}
		catch (Exception $e){
			$this->mensagem = 'Erro ao colocar protocolo em execução: '. $e->getMessage();
			TTransaction::rollback();
		}
	}

}
?>