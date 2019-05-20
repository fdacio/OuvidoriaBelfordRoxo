<?php
class GravaProtocoloWS{

	private $protocolo;
	private $json;

	public function gravarDados(){
		
		$jsonText = file_get_contents('php://input');

		$arrayProtocolo = array();
		
		$arrayProtocolo = json_decode(utf8_encode($jsonText), TRUE);

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
		
		$this->protocolo = new Protocolo();
		$this->protocolo->PRO_NUMERO = $numero;
		$this->protocolo->PRO_ANO = date("Y");
			
		$this->protocolo->PRO_DATA = date("Y-m-d H:i:s");
		$this->protocolo->USU_ATENDIMENTO = 0;
		$this->protocolo->PRO_STATUS = 1;

		$this->protocolo->PRO_NOME = $arrayProtocolo["nome"];
		$this->protocolo->PRO_ENDERECO = $arrayProtocolo["endereco"].", ".$arrayProtocolo["numero"];
		$this->protocolo->PRO_BAIRRO = $arrayProtocolo['bairro'];
		$this->protocolo->PRO_MUNICIPIO = 'Belfor Roxo';
		$this->protocolo->PRO_UF = 'RJ';
		$this->protocolo->PRO_CELULAR = $arrayProtocolo['celular'];
		$this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO = $arrayProtocolo['reclamacao'];

		try	{
				
			TTransaction::open();
			$this->protocolo->store();
			TTransaction::close();
			$this->json = "{'Status':'OK',
					'NumeroAno':'". $this->protocolo->PRO_NUMERO_ANO . "',
					'Numero':'".$this->protocolo->PRO_NUMERO."',
					'Ano':'".$this->protocolo->PRO_ANO."'}";
		}
		catch (Exception $e){
			$this->json = "{'Status':'ERROR','Error':'Tente mais tarde'}";
			TTransaction::rollback();
		}
			
		echo $this->json;
	}

}