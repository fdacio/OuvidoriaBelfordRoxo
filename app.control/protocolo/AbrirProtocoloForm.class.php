<?php
class AbrirProtocoloForm extends Page{
	private $html;
	private $mensagem;
	private $protocolo;
	private $operacao;

	public function __construct(){
		$this->html = file_get_contents('app.view/protocoloabrir.html');
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

	private function setObjetcs()
	{
		$idProtocolo = isset($_GET['key'])?$_GET['key']:'';
		TTransaction::open();
		if($idProtocolo != '')
		{
			$niveUsuario = TSession::getValue('SISPBR_OUVID_USU_NIVEL');
			if($niveUsuario == '1')
			{
				$this->protocolo = new Protocolo($idProtocolo);
				$this->operacao = "Alterar";
				
			}
			else 
			{
				$action1 = new TAction(array($this, 'goList'));
				new TMessage("Operação permitida somente para administrador.",$action1," app.images/info.png");
				
			}
			
		}else 
		{
			$this->protocolo = new Protocolo();
			$this->protocolo->PRO_MUNICIPIO = 'BELFORD ROXO';
			$this->operacao = "Abrir";
		}
		TTransaction::close();
	}

	private function setDisplayButtons(){
		if($this->operacao == 'Abrir')
		{
			$this->html = str_replace('#BUTTON_ABRIR_DISPLAY','',$this->html);
			$this->html = str_replace('#BUTTON_ALTERAR_DISPLAY','button_hide',$this->html);
			$this->html = str_replace('#LEGEND_FORM',' Abrir Protocolo ',$this->html);
			$this->html = str_replace('#DISPLAY_CRONOMETRO','',$this->html);
			

		}
		else if($this->operacao == 'Alterar')
		{
			$this->html = str_replace('#BUTTON_ABRIR_DISPLAY','button_hide',$this->html);
			$this->html = str_replace('#BUTTON_ALTERAR_DISPLAY','',$this->html);
			$this->html = str_replace('#LEGEND_FORM',' Alterar Protocolo ',$this->html);
			$this->html = str_replace('#DISPLAY_CRONOMETRO','button_hide',$this->html);

		}
		else
		{
			$this->html = str_replace('#BUTTON_ABRIR_DISPLAY','button_hide',$this->html);
			$this->html = str_replace('#BUTTON_ALTERAR_DISPLAY','button_hide',$this->html);
			$this->html = str_replace('#LEGEND_FORM','',$this->html);
			$this->html = str_replace('#DISPLAY_CRONOMETRO','button_hide',$this->html);
		}
	}
	
	private function setDadosForm(){

		$this->html = str_replace('#PRO_ID',$this->protocolo->PRO_ID, $this->html);
		$this->html = str_replace('#PRO_NUMERO_ANO', $this->protocolo->PRO_NUMERO_ANO, $this->html);
		$this->html = str_replace('#PRO_TEMPO_DECORRIDO', ($this->protocolo->PRO_TEMPO_DECORRIDO), $this->html);
		$this->html = str_replace('#PRO_NUMERO',$this->protocolo->PRO_NUMERO, $this->html);
		$this->html = str_replace('#PRO_ANO',$this->protocolo->PRO_ANO, $this->html);
		
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

		$this->setDisplayButtons();
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

		$this->setObjetcs();
		
		$this->protocolo->PRO_ID = $_POST['pro_id'];
		if($this->protocolo->PRO_ID == '')
		{
			TTransaction::open();
    		$conn = TTransaction::get();
            $sql = new TSqlSelect;
            $sql->addColumn('max(PRO_NUMERO) as MAX_NUMERO');
            $sql->setEntity('PROTOCOLO');
            $sql->setCriteria(new TCriteria(new TFilter("PRO_ANO", "=", date("Y"))));
            $result= $conn->Query($sql->getInstruction());
            $row = $result->fetch();
            $numero = $row[0];
            $numero++;
            $this->protocolo->PRO_NUMERO = $numero;
        	$this->protocolo->PRO_ANO = date("Y");
        	$this->protocolo->PRO_TEMPO_DECORRIDO = TFuncoes::strToTime($_POST['cronometro']);
        	
			$this->protocolo->PRO_DATA = date("Y-m-d H:i:s");
			$this->protocolo->USU_ATENDIMENTO = TSession::getValue('SISPBR_OUVID_USU_ID');
			$this->protocolo->PRO_STATUS = 1;        	
			
		}else 
		{
			$this->protocolo->PRO_NUMERO = $_POST['pro_numero'];
			$this->protocolo->PRO_ANO = $_POST['pro_ano'];
		}
		
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
		
		

		if($this->protocolo->PRO_NOME == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#protocolo_nome_requerido', 'campo_requerido', $this->html);
		}

		if($this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO == ''){
			$this->mensagem = "Informe os campos obrigatórios <br />";
			$this->html = str_replace('#protocolo_pedido_requerido', 'campo_requerido', $this->html);
		}

		if($this->mensagem == ''){
			try	{
					
				TTransaction::open();
				$this->protocolo->store();
				TTransaction::close();
				
				$action1 = new TAction(array(new ProtocoloAbertoList(), 'onReload'));
				if($this->protocolo->PRO_ID == '')
				{
					new TMessage("Protocolo aberto com sucesso!",$action1," app.images/info.png");	
				}
				else
				{
					new TMessage("Protocolo alterado com sucesso!",$action1," app.images/info.png");
				}
				
				
			}
			catch (Exception $e){
				$this->mensagem = 'Erro ao abrir protocolo: '. $e->getMessage();
				TTransaction::rollback();
			}
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