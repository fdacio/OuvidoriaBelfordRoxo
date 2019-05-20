<?php
class ProtocoloForm extends Page{
	private $html;
	private $mensagem;
	private $protocolo;

	public function __construct(){
		$this->html = file_get_contents('app.view/protocoloedit.html');
		$this->protocolo = new Protocolo();
		$this->protocolo->PRO_MUNICIPIO = 'BELFORD ROXO';
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
		$this->html = str_replace('#PRO_DATA', $this->protocolo->DATA, $this->html);
		$this->html = str_replace('#PRO_ATENDENTE', $this->protocolo->ATENDENTE, $this->html);
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
		$this->html = str_replace('#PRO_DESTINO', ($this->protocolo->PRO_DESTINO), $this->html);
		$this->html = str_replace('#PRO_PEDIDO_RECLAMACAO_SUGESTAO', ($this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO), $this->html);
		$this->html = str_replace('#PRO_OBSERVACAO', ($this->protocolo->PRO_OBSERVACAO), $this->html);
		$this->html = str_replace('#PRO_RESULTADO', ($this->protocolo->PRO_RESULTADO), $this->html);
		$this->html = str_replace('#PRO_EXECUCAO_EM', $this->protocolo->DATA_FINALIZACAO, $this->html);
		$this->html = str_replace('#PRO_RESPONSAVEL_EXECUCAO', $this->protocolo->ATEND_RESULTADO, $this->html);
		
		$options = "";
		$status = $this->protocolo->arrayStatus;
		foreach ($status as $key=>$value)
		{
			if($this->protocolo->PRO_STATUS == $key)
			{
				$selected = "selected='selected'";
			}
			else
			{
				$selected = "";
			}
			
			$options .="<option value=".$key." ".$selected." >".$value."</option>";	
		}
		$this->html = str_replace('<option>#OPTIONS</option>', $options, $this->html);
		
		
		
	}

	private function setMensagemForm()
	{
		if($this->mensagem){
			$this->html = str_replace('#MSG', $this->mensagem, $this->html);
		}else{
			$this->html = str_replace('#MSG', '', $this->html);
		}

	}


	public function onSave()
	{

		$this->protocolo->PRO_ID = $_POST['pro_id'];
		$this->protocolo->PRO_NOME = $_POST['protocolo_nome'];
		$this->protocolo->PRO_NASCIMENTO = TFuncoes::formataDataUSA($_POST['protocolo_nascimento']);
		$this->protocolo->PRO_ENDERECO = $_POST['protocolo_endereco'];
		$this->protocolo->PRO_BAIRRO = $_POST['protocolo_bairro'];
		$this->protocolo->PRO_MUNICIPIO = $_POST['protocolo_municipio'];
		$this->protocolo->PRO_UF = $_POST['protocolo_uf'];
		$this->protocolo->PRO_CEP = $_POST['protocolo_cep'];
		$this->protocolo->PRO_CELULAR = $_POST['protocolo_celular'];
		$this->protocolo->PRO_TELEFONE = $_POST['protocolo_telefone'];
		$this->protocolo->PRO_EMAIL = $_POST['protocolo_email'];
		$this->protocolo->PRO_REDESOCIAL = $_POST['protocolo_redesocial'];
		$this->protocolo->PRO_DESTINO = $_POST['protocolo_destino'];
		$this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO = $_POST['protocolo_pedido'];
		$this->protocolo->PRO_OBSERVACAO = $_POST['protocolo_observacao'];
		$this->protocolo->PRO_STATUS = $_POST['protocolo_status'];
		

		if($this->protocolo->PRO_NOME == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#protocolo_nome_requerido', 'campo_requerido', $this->html);
		}

		if($this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#protocolo_pedido_requerido', 'campo_requerido', $this->html);
		}

		if($this->protocolo->PRO_STATUS == '0'){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#protocolo_status_requerido', 'campo_requerido', $this->html);
		}
		
		if($this->mensagem == ''){
			try	{
					
				TTransaction::open();
				$this->protocolo->store();
				TTransaction::close();
				
				$action1 = new TAction(array(new ProtocoloList(), 'onReload'));
				new TMessage("Protocolo alterado com sucesso!",$action1," app.images/info.png");				
			}
			catch (Exception $e){
				$this->mensagem = 'Erro ao alterar protocolo: '. $e->getMessage();
				TTransaction::rollback();
			}
		}
	}

}
?>