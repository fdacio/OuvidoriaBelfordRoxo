<?php
abstract class TFormData extends TPage {

	private $form;//formulário
	private $class;//classe que representa o modelo de dados
	private $classList;
	private $nameForm;
	private $labelForm;
	private $qtdeLinhasTabs;
	private $PK;
	private $FIELDS_TABLE;
	private $INPUTS_FORM;
	private $FIELDS_OBJECT;

	private $FIELDS_FORM_REQUERID;
	private $FIELDS_FORM_VALIDATE_INFO;
	private $FIELDS_FORM_UNIQUE;
	private $msg;

	public function getForm()
	{
		return $this->form;
	}
	public function setForm($form)
	{
		$this->form = $form;
	}


	public function setClass($class)
	{
		$this->class = $class;
	}
	public function getClass()
	{
		return $this->class;
	}


	public function setClassList($classList)
	{
		$this->classList = $classList;
	}
	public function getClassList()
	{
		return $this->classList;
	}



	public function setNameForm($nameForm)
	{
		$this->nameForm = $nameForm;
	}
	public function setLabelForm($labelForm)
	{
		$this->labelForm = $labelForm;
	}

	public function setQtdeLinhasTab($qtdeLinhasTab)
	{
		$this->qtdeLinhasTabs = $qtdeLinhasTab;
	}


	public function setPK($PK)
	{
		$this->PK = $PK;
	}
	public function getPK()
	{
		return $this->PK;
	}


	public function setFieldsTable($FIELDS_TABLE)
	{
		$this->FIELDS_TABLE = $FIELDS_TABLE;
	}
	public function getFieldsTable()
	{
		return $this->FIELDS_TABLE;
	}


	public function setInputsForm($INPUTS_FORM)
	{
		$this->INPUTS_FORM = $INPUTS_FORM;
	}
	public function getInputsForm()
	{
		return $this->INPUTS_FORM;
	}

	public function setFieldsObjetc($FIELDS_OBJECT)
	{
		$this->FIELDS_OBJECT = $FIELDS_OBJECT;
	}
	public function getFieldsObjetc()
	{
		return $this->FIELDS_OBJECT;
	}


	public function setFieldsFormRequerid($FIELDS_FORM_REQUERID)
	{
		$this->FIELDS_FORM_REQUERID = $FIELDS_FORM_REQUERID;
	}
	public function getFieldsFormRequerid()
	{
		return $this->FIELDS_FORM_REQUERID;
	}


	public function setFieldsFormUnique($FIELDS_FORM_UNIQUE)
	{
		$this->FIELDS_FORM_UNIQUE = $FIELDS_FORM_UNIQUE;
	}
	public function getFieldsFormUnique()
	{
		return $this->FIELDS_FORM_UNIQUE;
	}


	public function setFieldsFormValidateInfo($FIELDS_FORM_VALIDATE_INFO)
	{
		$this->FIELDS_FORM_VALIDATE_INFO = $FIELDS_FORM_VALIDATE_INFO;
	}
	public function getFieldsFormValidateInfo()
	{
		return $this->FIELDS_FORM_VALIDATE_INFO;
	}


	/*
	 * método construtor
	 * Cria a página e o formulário de cadastro
	 */

	function __construct()
	{
		parent::__construct();

		$divForm = new TElement('div');
		$divForm->class='div_form';

		//instancia um formulário
		$this->setForm(new TForm($this->nameForm));

		$fieldset = new TElement('fieldset');
		$legend = new TElement('legend');
		$legend->add('Cadastro de ' . $this->labelForm);
		$fieldset->add($legend);

		$tableFields = new TTable();
		$arrayFieldsForm = array();
		foreach($this->INPUTS_FORM as $field=>$fieldsInfo)
		{
			
			$name = $field;
			$label = $fieldsInfo[0];
			$size = (int)$fieldsInfo[1];
			$editable =($fieldsInfo[2]=="true")?1:0;
			$typeWidget = $fieldsInfo[3];
			if(isset($fieldsInfo[4])){
				$datasource = $fieldsInfo[4];
			}else{
				$datasource ="";
			}
			if(isset($fieldsInfo[5])){
				$mask = $fieldsInfo[5];
			}else{
				$mask = "";
			}
			if(isset($fieldsInfo[6])){
				$widgetAux = $fieldsInfo[6];
			}else{
				$widgetAux = "";
			}

			$object = new $typeWidget($name);
			$object->setProperty('id',$name);
			$object->setEditable($editable);
			$object->addClass('sizefiled_'.$size);

			if($typeWidget == 'TCombo'){
				$object->addItems($datasource);
			}

			if($typeWidget == 'TCheckButton'){
				$object->setValue('1');
				$labelCheckBox = $fieldsInfo[1];
				$object->setLabel($labelCheckBox);
			}

			if($typeWidget == 'TCheckGroup'){
				$object->setValue('1');
				$object->addItems($datasource);
			}

			if($typeWidget == 'TRadioGroup'){
				$object->setValue('1');
				$object->addItems($datasource);
			}

			if($typeWidget == 'THidden'){
				$object->setValue($datasource);
			}

			if($mask != ""){
				$object->addClass('mask_'.$mask.'_mask');
			}

			if(in_array($name,$this->getFieldsTable()))	{
				$arrayFieldsForm[]= $object;
			}


			$row=$tableFields->addRow();

			$spanFieldRequery = '';
			if(in_array($label, $this->FIELDS_FORM_REQUERID)){
				$spanFieldRequery = new TElement('span');
				$spanFieldRequery->add('*');
				$spanFieldRequery->class='spanFieldRequery';
			}
			
			if($typeWidget != 'THidden')
			{
				$labelField = new TLabelField($label.':');
				$labelField->setProperty('for',$object->getName());
				$cell = $row->addCell($labelField);
			}else{
				$cell = $row->addCell('');
			}
			
			$cell->add($spanFieldRequery);
			$cell->class='labelfield';
			$cell = $row->addCell($object);
			


			/*
			 * Local para adicionar botao da consulta de FK;
			 *
			 */

			if($widgetAux != ''){
				$typeWidget = $widgetAux[0];
				$nome = $widgetAux[1];
				$valor = $widgetAux[2];
				$objAux = new $typeWidget($nome);
				$objAux->setValue($valor);
				$cell->add($objAux);
				if($typeWidget == 'TCheckButton')
				{
					$label = new TLabelField($valor);
					$cell->add($label);
				}
			}
			unset($object);
		}

		// cria um botão de salvar para o formulário
		$button1=new TButton('save');
		$button1->setProperty('id', 'save');
		// define a ação do botão de salvar
		$action1 = new TAction(array($this, 'onSave'));
		if(isset($_GET['pag'])){
			$action1->setParameter('pag',$_GET['pag']);
		}
		$button1->setAction($action1, 'Salvar');

		// cria um botão de cancelar para o formulário
		$button2=new TButton('cancel');
		$button2->setProperty('id', 'cancel');
		// define a adição do botão de cancelar
		$button2->setAction(new TAction(array($this, 'goList')), 'Cancelar');

		// cria um elemento "div" que vai conter os botões do formulário
		$divButtons=new TElement('div');
		$divButtons->class=	'divButtons';
		$divButtons->add($button1);
		$divButtons->add($button2);
		$row=$tableFields->addRow();
		$row->addCell("");
		$row->addCell($divButtons);

		$arrayFieldsForm[]=$button1;
		$arrayFieldsForm[]=$button2;

		$divMsgFieldRequired=new TElement('div');
		$divMsgFieldRequired->class='spanFieldRequery';
		$divMsgFieldRequired->add('*Campos de preenchimento obrigatório');
		$row=$tableFields->addRow();
		$cell = $row->addCell($divMsgFieldRequired);
		$cell->colspan='2';

		$fieldset->add($tableFields);
		$this->form->add($fieldset);
		$this->form->setFields($arrayFieldsForm);
			
		$divForm->add($this->form);
		parent::add($divForm);
	}


	/*
	 * método onEdit
	 * Edita os dados de um registro
	 */
	function onEdit($param)
	{

		try
		{
			$niveUsuario = TSession::getValue('SISPBR_OUVID_USU_NIVEL');
			
			if (isset($param['key']))
			{
				
				if($niveUsuario == '1'){
						
					// inicia transação com o banco
					TTransaction::open();
					// obtém o Objeto de Dado de acordo com o parâmetro
					$obj = new $this->class($param['key']);

					foreach ($obj->toArray() as $key=>$value)
					{
						if(TFuncoes::representaUmaDataUSA($value))
						{
							$obj->$key = TFuncoes::formataDataBr($value);
						}

					}
					// lança os dados no formulário
					$this->form->setData($obj);
					// finaliza a transação
					TTransaction::close();
				}else{
					$this->msg = "Operação permitida somente para administrador.";
					$this->goList();
				}
					
			}
			else {
				echo $this->class;
				if(($this->class ==	'Usuario')&&($niveUsuario > 1))
				{
					$this->msg = "Operação permitida somente para administrador.";
					$this->goList();
				}
			}
		}
		catch (Exception $e) //em caso de exceção
		{
			//exibe a mensagem gerada pela exceção
			$action1 = new TAction(array($this, 'onEdit'));
			$action1->setParameter('key',$param['key']);
			new TMessage('<b>Erro</b>'.'<br />'.$e->getMessage(),$action1,"app.images/error.png");
			//desfaz todas alterações no banco de dados
			TTransaction::rollback();
		}
	}

	/*
	 * método onSave
	 * Executado quando o usuário clicar no botão salvar
	 */
	function onSave()
	{
		/*
		 * Instancia o objeto a ser salvo através do formulário de edição
		 */
		$obj = $this->form->getData($this->class);	
			
		if($this->getFieldsObjetc() > 0)
		{

			foreach ($this->getFieldsObjetc() as $key=>$value)
			{
				
				$obj->$key = $value;
					
			}
		}
		
		/*
		 *  Instancia um objeto de validação do dados;
		 */
		$validate = new TValidate($this);
		$validate->validate();

		if($validate->getMessage() == '')
		{
			try
			{
				// inicia transação com o banco
				TTransaction::open();
				//Varre os valores que podem representa
				//uma data, para poder transformar no formato
				//yyyy-mm-dd
				foreach ($obj->toArray() as $key=>$value)
				{
					if(TFuncoes::representaUmaDataBr($value))
					{
						$obj->$key = TFuncoes::formataDataUSA($value);
					}
					if(strpos($key,"SENHA")>0)
					{
						$obj->$key = TFuncoes::passwordMD5($value);
					}
				}
				//armazena o objeto no banco de dados
				$obj->store();
				// finaliza a transação
				TTransaction::close();
				// exibe mensagem de sucesso
				$this->form->setData($obj);

				$action1 = new TAction(array($this, 'goList'));
				if(isset($_GET['pag'])){
					$pag = $_GET['pag'];
					$action1->setParameter('pag',$pag);
				}
				new TMessage('Dados salvos com sucesso!',$action1,"app.images/info.png");
			}
			catch (Exception $e)
			{
				// lança os dados do produto no formulário
				$this->form->setData($obj);

				//exibe a mensagem gerada pela exceção
				new TMessage('<b>Erro</b>'.'<br />'.$e->getMessage(),null,"app.images/error.png");

				// desfaz todas alterações no banco de dados
				TTransaction::rollback();
			}
		}
		else
		{
			$this->form->setData($obj);
			new TMessage($validate->getMessage(),null,"app.images/error.png");
		}
	}

	function goList($msg='')
	{
		$classList = $this->getClassList();
		$objList = new $classList;
		$action = new TAction(array($objList,'onReload'));
		$action->setParameter('pag',$_GET['pag']);
		if($this->msg != '')
		{
			$action->setParameter('msg',$this->msg);
		}
		$url = $action->serialize();
		header("Location: $url");
	}

}

?>