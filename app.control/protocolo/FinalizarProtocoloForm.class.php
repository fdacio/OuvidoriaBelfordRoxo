<?php
class FinalizarProtocoloForm extends Page{
	private $html;
	private $mensagem;
	private $protocolo;

	public function __construct()
	{
		$this->html = file_get_contents('app.view/protocolofinalizar.html');
		$this->protocolo = new Protocolo();
	}

	public function show()
	{
		if(TSession::getValue('SISPBR_OUVID_USU_ID'))
		{
			parent::run();
			$this->setDadosForm();
			$this->setMensagemForm();
			echo $this->html;
		}
		else
		{
			$login = new Login();
			$login->doLogout();			
		}
	}
	
	public function onFinalizar()
	{
		$this->setObjetcs();
				
	}

	private function setObjetcs()
	{
		$idProtocolo = isset($_GET['key'])?$_GET['key']:$_POST['pro_id'];
		
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

	private function setMensagemForm()
	{
		if($this->mensagem){
			$this->html = str_replace('#MSG', $this->mensagem, $this->html);
		}else{
			$this->html = str_replace('#MSG', '', $this->html);
		}

	}


	public function Finalizar()
	{
		$this->setObjetcs();
		$this->protocolo->PRO_ID = $_POST['pro_id'];
		$this->protocolo->PRO_RESULTADO = $_POST['protocolo_resultado'];		
		$this->protocolo->PRO_DATA_FINALIZACAO = date("Y-m-d H:i:s");
		$this->protocolo->USU_RESULTADO = TSession::getValue('SISPBR_OUVID_USU_ID');
		$this->protocolo->PRO_STATUS = 3;        	

		if($this->protocolo->PRO_RESULTADO == '')
		{
			
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#protocolo_resultado_requerido', 'campo_requerido', $this->html);
		}

		if($this->mensagem == ''){
			try	{
					
				TTransaction::open();
				$this->protocolo->store();
				TTransaction::close();
				
				$action1 = new TAction(array(new ProtocoloFinalizadoList(), 'onReload'));
				new TMessage("Protocolo finalizado com sucesso!",$action1," app.images/info.png");
				
			}
			catch (Exception $e){
				$this->mensagem = 'Erro ao finalizar protocolo: '. $e->getMessage();
				TTransaction::rollback();
			}
		}
		else 
		{
			$this->setDadosForm();	
		}
		
	}

}
?>