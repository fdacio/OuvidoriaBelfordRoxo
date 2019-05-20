<?php
class TValidate{
	private $classFormData;
	private $messages = array();
	private $fields = array(); 
	
	private function setMessage($msg)
	{
		$this->messages[]=$msg;
	}
	
	public function getMessage()
	{
		if(count($this->messages)>0)
		{
			$ul = new TElement('ul');
			foreach ($this->messages as $msg)
			{
				$li = new TElement('li');
				$li->add($msg);
				$ul->add($li);
			}
			
			return $ul;
		}
		else 
			return "";
	}

	
	private function setFields($field)
	{
		$this->fields[]=$field;	
	}
	
	public function getFields()
	{
		return $this->fields;
	}
	
	function __construct($classFormData)
	{
		$this->classFormData = $classFormData;		
	}
	
	/**
	 * 
	 * Método que usa as informções do formulário
	 * para realizar as validação dos dádos do mesmo
	 * 
	 */
	public function validate()
	{
		/*
		 * Instancia um objeto TFormaData para 
		 * capturar os campos requeridos, 
		 * os campos a serem validados os valores
		 * e os campos que são unicos no objeto
		 */
		//print_r($this->classFormData);
		
		$objFormData = new $this->classFormData;
		$arrayInputsForm = $objFormData->getInputsForm();
		$arrayFieldsRequery = $objFormData->getFieldsFormRequerid();
		$arrayFieldsValidateInfo = $objFormData->getFieldsFormValidateInfo();
		$arrayFieldsUnique = $objFormData->getFieldsFormUnique();
		$pkRepository = $objFormData->getPK();
		
		/*
		 * Instacia o objeto a ser salvo, baseado na classe
		 * que o formulario representa
		 */
		$classObj = $objFormData->getClass();
		$objForm = $objFormData->getForm();
		$objASerSalvo = $objForm->getData($classObj);		
		
		/*
		 * Converte o objeto em um array para facilitar 
		 * a checagem dos valores 
		 */
		$arrayDadosASerSalvo = $objASerSalvo->toArray();
		
		
		/*
		 * Varre os campos obrigatários do formulario e 
		 * comparar com os objeto a ser salvo
		 */
		foreach($arrayFieldsRequery as $key=>$value)
		{
			if(($arrayInputsForm [$key][3]=='TEntry')&&($arrayDadosASerSalvo[$key] == ''))
			{ 
				$this->setMessage('Campo <b>'.$value.'</b> é obrigatório!');
				$this->setFields($key);
			}
			
			if(($arrayInputsForm [$key][3]=='TText')&&($arrayDadosASerSalvo[$key] == ''))
			{ 
				$this->setMessage('Campo <b>'.$value.'</b> é obrigatório!');
				$this->setFields($key);
			}
			
			if(($arrayInputsForm [$key][3]=='TPassword')&&($arrayDadosASerSalvo[$key] == ''))
			{ 
				$this->setMessage('Campo <b>'.$value.'</b> é obrigatório!');
				$this->setFields($key);
			}
			
			if(($arrayInputsForm [$key][3]=='TCombo')&&($arrayDadosASerSalvo[$key] == 0))
			{ 
				$this->setMessage('Campo <b>'.$value.'</b> é obrigatório!');
				$this->setFields($key);
			}
		}

		/*
		 * Varre os campos a serem validados os valores
		 * Ex: Data, Inteiro, CPF/CNPJ, EMAIL etc. 
		 * comparar com os objeto a ser salvo
		 */
		if(count($arrayFieldsValidateInfo) > 0){
			foreach ($arrayFieldsValidateInfo as $fieldValidate=>$arrayLabelTipo)
			{
				$label = $arrayLabelTipo[0];
				$tipo  = $arrayLabelTipo[1];
				$valor = $arrayDadosASerSalvo[$fieldValidate];
				if($valor)
				{
					if($tipo == 'DATA')
					{
						$data = $valor;
						if(!(TFuncoes::validaData($data)))
						{
							$this->setMessage('Campo <b>'.$label.'</b> éstá inválido!');
							$this->setFields($fieldValidate);
						}
					}
					
					if($tipo == 'INT')
					{
						$inteiro = $valor;
	  					$inteiro = str_replace(" ","",trim($inteiro));
	        			$bool= eregi("^([0-9])+$",$inteiro);					
						if(!($bool))
						{
							$this->setMessage('Campo <b>'.$label.'</b> está inválido!');
							$this->setFields($fieldValidate);
						}
					}
					
					if($tipo == 'FLOAT')
					{
						$float = $valor;
	  					$float = str_replace(" ","",trim($float));
	        			$bool= eregi("^([0-9])+([\.|,]([0-9])*)?$",$float);					
						if(!($bool))
						{
							$this->setMessage('Campo <b>'.$label.'</b> está inválido!');
							$this->setFields($fieldValidate);
						}
					}
					
					if($tipo == 'CPFCNPJ')
					{
						$cpf_cnpj = TFuncoes::desformataCPFCNPJ($valor);
						if(!(TFuncoes::validaCPFCNPJ($cpf_cnpj)))
						{
							$this->setMessage('Campo <b>'.$label.'</b> éstá inválido!');
							$this->setFields($fieldValidate);
						}
					}	
					if($tipo =='EMAIL')
					{
						$email = $valor;
						if(!(TFuncoes::validaEmail($email)))
						{
							$this->setMessage('Campo <b>'.$label.'</b> éstá inválido!');
							$this->setFields($fieldValidate);
						}
						
					}
				}
			}
		}
		
		/*
		 * Varre os campos que só podem existir uma unica vez 
		 * no banco de dados. Ex: Não pode haver um mesmo número
		 * de CPF/CNPJ para pessoas diferentes e comparar com os 
		 * do objeto a ser salvo
		 */
		foreach($arrayFieldsUnique as $key=>$label)
		{
			$valor = $arrayDadosASerSalvo[$key];
			if($valor)
			{
				TTransaction::open();
				$pk = $arrayDadosASerSalvo[$pkRepository];
				$repository = new TRepository($objFormData->getClass());
				$criteria = new TCriteria;
				$filter = new TFilter($key, '=', "$valor");
				$criteria->add($filter);
				if($pk)//Salvando um update
				{
					$filter = new TFilter($pkRepository, '<>', $pk);
					$criteria->add($filter);	
				}
				
				$registros = $repository->load($criteria);
				
				if(count($registros)>0)
				{
					$this->setMessage('<b>'.$label.'</b> já existe na base de dados!');
					$this->setFields($key);
				}
				TTransaction::close();
			}
			
		}
		
	}
	/*
	 * Fim do método de validação.
	 */
	
	
}	
?>