<?php
class ProtocoloFilterReportForm extends Page{

	private $html;
	private $mensagem;
	private $protocolo;


	public function __construct(){
		$this->html = file_get_contents('app.view/protocolofilterreportform.html');
		$this->protocolo = new Protocolo();
	}

	public function show(){
		if(TSession::getValue('SISPBR_OUVID_USU_ID')){
			parent::run();
			$this->setDadosForm();
			$this->setMensagemForm();
			echo $this->html;
		}else{
			header('Location:?class=Login');
		}
	}

	private function setDadosForm(){

		if($this->protocolo->ORDERBY == 'PRO_NUMERO'){
			$this->html = str_replace('title="#CHECKED_PRO_NUMERO"', "checked='checked'", $this->html);
			$this->html = str_replace('title="#CHECKED_PRO_DATA"', "", $this->html);
		}else{
			$this->html = str_replace('title="#CHECKED_PRO_NUMERO"', "", $this->html);
			$this->html = str_replace('title="#CHECKED_PRO_DATA"', "checked='checked'", $this->html);
		}

		$situacoes = $this->protocolo->arrayStatus;
		$options = "";
		foreach ($situacoes as $key=>$value){
			if($this->protocolo->PRO_STATUS == $value){
				$selected = "selected='selected'";
			}else{
				$selected = "";
			}
			$options .="<option value=".$key." ".$selected." >".$value."</option>";
		}
		$this->html = str_replace('<option>#OPTIONS_STATUS</option>', $options, $this->html);


		$this->html = str_replace('#PRO_DATA1', $this->protocolo->PRO_DATA1, $this->html);
		$this->html = str_replace('#PRO_DATA2', $this->protocolo->PRO_DATA2, $this->html);

	}

	private function setMensagemForm(){

		if($this->mensagem){
			$this->html = str_replace('#MSG', $this->mensagem, $this->html);
		}else{
			$this->html = str_replace('#MSG', '', $this->html);
		}

	}

	public function onGerar(){

		$niveUsuario = TSession::getValue('SISPBR_OUVID_USU_NIVEL');
		if($niveUsuario == '1')
		{
		
			ob_end_clean();
			$this->protocolo->ORDERBY = $_POST['order_by'];
			$this->protocolo->PRO_STATUS = $_POST['status'];
			$this->protocolo->PRO_DATA1 = $_POST['pro_data1'];
			$this->protocolo->PRO_DATA2 = $_POST['pro_data2'];
	
			try{
				TTransaction::open();
				$criteria = new TCriteria();
					
				$criteria->setProperty('order', $this->protocolo->ORDERBY);
					
				if($this->protocolo->PRO_STATUS > 0)
				{
					$criteria->add(new TFilter('PRO_STATUS', '=', $this->protocolo->PRO_STATUS));
				}
	
				if(($this->protocolo->PRO_DATA1 != '')&&($this->protocolo->PRO_DATA2 !=''))
				{
					if((TFuncoes::validaData($this->protocolo->PRO_DATA1))&&(TFuncoes::validaData($this->protocolo->PRO_DATA2)))
					{
					
						if(!(TFuncoes::validaPeriodo($this->protocolo->PRO_DATA1, $this->protocolo->PRO_DATA2)))
						{
							$this->mensagem = "Período de abertura inválido";
						
						}else
						{
							$data1USA = TFuncoes::formataDataUSA($this->protocolo->PRO_DATA1)." 00:00:00";
							$data2USA = TFuncoes::formataDataUSA($this->protocolo->PRO_DATA2)." 23:59:59";
								
							$criteria->add(new TFilter('PRO_DATA', '>=', $data1USA));
							$criteria->add(new TFilter('PRO_DATA', '<=', $data2USA));
						}
					}
					else 
					{
						$this->mensagem = "Data inválida";
					}	
				}
	
				if($this->mensagem == '')
				{
					$repository = new TRepository('Protocolo');
					$protocolos = $repository->load($criteria);
					if(isset($_POST['btnGerarPdf']))
					{
						$report = new ProtocoloReport();
						$report->setContent($protocolos);
						$report->download('ReportProtocolo.pdf');
					
					}else if(isset($_POST['btnGerarExcel']))
					{
						$excel = new ProtocoloExcel();
						$excel->setContent($protocolos);
						$excel->download('ExcelProtocolo.xls');					
					}
				}
	
				TTransaction::close();
	
			}catch (Exception $e)
			{
				new TMessage('Erro ao gerar arquivo: '. $e->getMessage(), NULL," app.images/error.png");
				TTransaction::rollback();
			}
		}else{
			new TMessage("Operação permitida somente para administrador.",NULL," app.images/info.png");
		}	

	}

}
?>