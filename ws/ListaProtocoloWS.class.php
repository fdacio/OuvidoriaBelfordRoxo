<?php
class ListaProtocoloWS{

	private $protocolo;
	
	public function getProtocolos($param){
		
		$ano    = isset($param['ano']) ? $param['ano'] : '';
		$numero = isset($param['numero']) ? $param['numero'] : '';
				
		$protocolosDB = array();
		$this->protocolo = new Protocolo();
		
		try{
			$criteria = new TCriteria();
			$criteria->add(new TFilter('PRO_ANO', '=', $ano));
			$criteria->add(new TFilter('PRO_NUMERO', '=', $numero));
			$criteria->setProperty('order', 'PRO_ID' . ' DESC');
			TTransaction::open();
			$repository = new TRepository('Protocolo');
			$protocolosDB = $repository->load($criteria);
			TTransaction::close();
			
			$json = '{"Status":"OK",
						"Ano":"",
						"Numero":"",
						"Data":"",
						"Situacao":"",
						"DataExecucao":"",
						"DataFinalizacao":"",
						"Reclamacao":"",
						"Resultado":""}';
			
			foreach ($protocolosDB as $this->protocolo)
			{
				$json = '{"Status":"OK",
						"Ano":"'.$this->protocolo->PRO_ANO.'",
						"Numero":"'.$this->protocolo->PRO_NUMERO.'",
						"Data":"'.$this->protocolo->PRO_DATA.'",
						"Situacao":"'.$this->protocolo->PRO_STATUS.'",
						"DataExecucao":"'.$this->protocolo->PRO_DATA_EXECUCAO.'",
						"DataFinalizacao":"'.$this->protocolo->PRO_DATA_FINALIZACAO.'",
						"Reclamacao":"'.$this->protocolo->PRO_PEDIDO_RECLAMACAO_SUGESTAO.'",
						"Resultado":"'.$this->protocolo->PRO_RESULTADO.'"}';

			}
			
			return $json;			
			
		}		
		catch (Exception $e){
			echo '{"Status":"ERROR","Error":"Tente mais tarde:' . $e->getMessage().'"}';
			TTransaction::rollback();
		}
		
	}
}