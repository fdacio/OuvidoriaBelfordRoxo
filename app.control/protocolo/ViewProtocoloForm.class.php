<?php
class ViewProtocoloForm extends Page{
	private $html;
	private $protocolo;
	

	public function __construct()
	{
		$this->html = file_get_contents('app.view/protocoloview.html');
	}

	public function show()
	{
		if(TSession::getValue('SISPBR_OUVID_USU_ID')){
			parent::run();
			$this->setDadosForm();
			echo $this->html;
		}else{
			$login = new Login();
			$login->doLogout();			
		}
	}
	
	public function onVisualizar()
	{
		$this->setObjetcs();
				
	}

	private function setObjetcs()
	{
		$idProtocolo = isset($_GET['key'])?$_GET['key']:$_POST['pro_id'];
		TTransaction::open();
		if($idProtocolo != '')
		{
			$this->protocolo = new Protocolo($idProtocolo);
			
		}else 
		{
			$this->protocolo = new Protocolo();
		}
		$this->usuario = new Usuario(TSession::getValue('SISPBR_OUVID_USU_ID'));
		TTransaction::close();
	}


	private function setDadosForm()
	{

		$this->html = str_replace('#PRO_ID',$this->protocolo->PRO_ID, $this->html);
		$this->html = str_replace('#PRO_NUMERO_ANO', $this->protocolo->PRO_NUMERO_ANO, $this->html);
		$this->html = str_replace('#PRO_NOME', $this->protocolo->PRO_NOME, $this->html);
		$this->html = str_replace('#PRO_NASCIMENTO', TFuncoes::formataDataBr($this->protocolo->PRO_NASCIMENTO), $this->html);
		$this->html = str_replace('#PRO_ENDERECO', $this->protocolo->PRO_ENDERECO, $this->html);
		$this->html = str_replace('#PRO_BAIRRO', $this->protocolo->PRO_BAIRRO, $this->html);
		$this->html = str_replace('#PRO_MUNICIPIO', $this->protocolo->PRO_MUNICIPIO, $this->html);
		$this->html = str_replace('#PRO_UF', $this->protocolo->PRO_UF, $this->html);
		$this->html = str_replace('#PRO_CEP', $this->protocolo->PRO_CEP, $this->html);
		$this->html = str_replace('#PRO_CELULAR', $this->protocolo->PRO_CELULAR, $this->html);
		$this->html = str_replace('#PRO_TELEFONE', $this->protocolo->PRO_TELEFONE, $this->html);
		$this->html = str_replace('#PRO_EMAIL', $this->protocolo->PRO_EMAIL, $this->html);
		$this->html = str_replace('#PRO_REDESOCIAL', $this->protocolo->PRO_REDESOCIAL, $this->html);		
		$this->html = str_replace('#PRO_PEDIDO_RECLAMACAO_SUGESTAO', ($this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO), $this->html);
		$this->html = str_replace('#PRO_OBSERVACAO', ($this->protocolo->PRO_OBSERVACAO), $this->html);
		$this->html = str_replace('#PRO_RESULTADO', ($this->protocolo->PRO_RESULTADO), $this->html);

	}

	public function View()
	{
		header('Location:?class=ProtocoloFinalizadoList&method=onReload');		
	}

}
?>