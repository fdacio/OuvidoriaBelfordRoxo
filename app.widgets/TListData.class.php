<?php
/*
 * classe TListData
 * Listagem de Dados
 */
abstract class TListData extends TPage
{
	private $form;
	private $datagrid;
	private $paginator;
	private $loaded;

	private $comboFieldSearch;
	private $comboCondition;
	private $valueSearch;
	private $class;
	private $classForm;
	private $classFormUpdate;
	private $labelForm;
	private $labelNewButton;
	private $regPorPagina;
	private $PK;
	private $FIELDS_SEARCH;
	private $FIELDS_GRID;
	private $FIELDS_GRID_LABEL;
	private $FIELDS_GRID_LENGTH;
	private $FIELDS_GRID_ALIGN;
	private $FIELDS_ORDER_BY;

	public function setClass($class)
	{
		$this->class = $class;
	}
	public function getClass()
	{
		return $this->class;
	}

	public function setClassForm($classForm)
	{
		$this->classForm = $classForm;
	}
	public function getClassForm()
	{
		return $this->classForm;
	}

	public function setClassFormUpdate($classFormUpdate)
	{
		$this->classFormUpdate = $classFormUpdate;
	}
	public function getClassFormUpdate()
	{
		return $this->classFormUpdate;
	}

	public function setLabelForm($labelForm)
	{
		$this->labelForm = $labelForm;
	}
	public function getLabelForm()
	{
		return $this->labelForm;
	}

	public function setLabelNewButton($labelNewButton)
	{
		$this->labelNewButton = $labelNewButton;
	}
	public function getLabelNewButton()
	{
		return $this->labelNewButton;
	}

	public function setRegistrosPorPagina($regPorPagina)
	{
		$this->regPorPagina = $regPorPagina;
	}
	public function getRegistrosPorPagina()
	{
		return $this->regPorPagina;
	}

	public function setPK($PK)
	{
		$this->PK=$PK;
	}
	public function getPK()
	{
		return $this->PK;
	}

	public function setFieldsSearch($FIELDS_SEARCH)
	{
		$this->FIELDS_SEARCH=$FIELDS_SEARCH;
	}
	public function getFieldsSearch()
	{
		return $this->FIELDS_SEARCH;
	}

	public function setFieldsGrid($FIELDS_GRID)
	{
		$this->FIELDS_GRID=$FIELDS_GRID;
	}
	public function getFieldsGrid()
	{
		return $this->FIELDS_GRID;
	}

	public function setFieldsGridLabel($FIELDS_GRID_LABEL)
	{
		$this->FIELDS_GRID_LABEL=$FIELDS_GRID_LABEL;
	}
	public function getFieldsGridLabel()
	{
		return $this->FIELDS_GRID_LABEL;
	}

	public function setFieldsGridLength($FIELDS_GRID_LENGTH)
	{
		$this->FIELDS_GRID_LENGTH=$FIELDS_GRID_LENGTH;
	}
	public function getFieldsGridLength()
	{
		return $this->FIELDS_GRID_LENGTH;
	}

	public function setFieldsGridAlign($FIELDS_GRID_ALIGN)
	{
		$this->FIELDS_GRID_ALIGN=$FIELDS_GRID_ALIGN;
	}
	public function getFieldsGridAlign()
	{
		return $this->FIELDS_GRID_ALIGN;
	}


	public function setFieldsOrderBy($FIELDS_ORDER_BY)
	{
		$this->FIELDS_ORDER_BY=$FIELDS_ORDER_BY;
	}
	public function getFieldsOrderBy()
	{
		return $this->FIELDS_ORDER_BY;
	}

	/*
	 * método construtor
	 * Cria a página, o formulário de buscas e a listagem
	 */
	public function __construct()
	{
		parent::__construct();

		$divList = new TElement('div');
		$divList->class='div_list';
		$label1 = new TElement('h2');
		$label1->add('Lista de ' . $this->labelForm);
		$divList->add($label1);

		$divSearchForm = new TElement('div');
		$divSearchForm->class='div_searchform';

		$this->form = new TForm('form_busca');
		$fieldset = new TElement('fieldset');
		$legend = new TElement('legend');
		$legend->add('Consulta de ' . $this->labelForm);
		$fieldset->add($legend);

		$table_search  = new TTable('tabela2');;

		//cria os campos do formulário
		$this->comboFieldSearch = new TCombo('comboFieldSearch');
		$this->comboFieldSearch->setProperty('id','comboFieldSearch');
		$this->comboFieldSearch->addClass('sizefiled_150');
		$this->comboFieldSearch->addItems($this->FIELDS_SEARCH);
		$keys = array_keys($this->FIELDS_SEARCH);
		$this->comboFieldSearch->setValue(array_shift($keys));
		$this->comboCondition = new TCombo('comboCondition');
		$this->comboCondition->addClass('sizefiled_150');
		$this->comboCondition->addItems(array('='=>'Igual',
		                                '%'=>'Contém', 
										'<'=>'Menor Que',
										'>'=>'Maior Que', 
										'<='=>'Menor Igual Que', 
										'>='=>'Maior Igual Que'));
		$this->comboCondition->setValue('%');

		$this->valueSearch = new TEntry('valueSearch');
		$this->valueSearch->setProperty('id','valueSearch');
		$this->valueSearch->addClass('sizefiled_220');
		$find_button = new TButton('search');
		$find_button->setProperty('id','search');
		$find_button->addClass('ui-icon-search');
		$find_button->setAction(new TAction(array($this, 'onReload')), 'Pesquisar');

		if($this->labelNewButton != ""){
			$obj = new $this->classForm;
			$new_button  = new TButton('add');
			$new_button->setProperty('id', 'add');
			$new_button->setAction(new TAction(array($obj, 'onEdit')), $this->labelNewButton);
		}else{
			$new_button  = NULL;
		}

		// adiciona uma linha para o campo descriçao
		$row_search=$table_search->addRow();
		$cell = $row_search->addCell($this->comboFieldSearch);
		$cell->width='100px';
		$cell = $row_search->addCell($this->comboCondition);
		$cell->width='100px';
		$cell = $row_search->addCell($this->valueSearch);
		$cell->width='100px';
		$row_search->addCell($find_button);
		// cria dois botões de açõe para o formulário

		$fieldset->add($table_search);
		//adiciona
		$this->form->add( $fieldset);
		// define quais são os campos do formulário
		$this->form->setFields(array($this->comboFieldSearch, $this->comboCondition, $this->valueSearch, $find_button, $new_button));

		$divSearchForm->add($this->form);

		// instancia objeto DataGrid
		$this->datagrid = new TDataGrid;
		for($i=0; $i < count($this->FIELDS_GRID); $i++)
		{
			$nameField   = $this->FIELDS_GRID[$i];
			$labelField  = $this->FIELDS_GRID_LABEL[$i];
			$lengthField = $this->FIELDS_GRID_LENGTH[$i];
			$alignField  = $this->FIELDS_GRID_ALIGN[$i];
			$this->datagrid->addColumn(new TDataGridColumn($nameField,$labelField,$alignField,$lengthField));
		}

			
		// instancia duas ações da DataGrid
		if(isset($this->classFormUpdate))
		$formEdit = new $this->classFormUpdate;
		else
		$formEdit = new $this->classForm;

		$action1 = new TDataGridAction(array($formEdit, 'onEdit'));
		$action1->setLabel('Editar');
		$action1->setImage('app.images/ico_edit.png');
		$action1->setField($this->PK);
		$action2 = new TDataGridAction(array($this, 'onDelete'));
		$action2->setLabel('Deletar');
		$action2->setImage('app.images/ico_delete.png');
		$action2->setField($this->PK);
		if(isset($_GET['pag'])){
			$action2->setParameter('pag',$_GET['pag']);
		}

		if(isset($_GET['pag']))
		{
			$action1->setParameter('pag',$_GET['pag']);
			$action2->setParameter('pag',$_GET['pag']);
		}
		if(isset($_GET['param']))
		{
			$action1->setParameter('param',$_GET['param']);
			$action2->setParameter('param',$_GET['param']);
		}

		if(isset($_GET['order']))
		{
			$action1->setParameter('order',$_GET['order']);
			$action2->setParameter('order',$_GET['order']);
		}

		// adiciona as ações à DataGrid
		$this->datagrid->addAction($action1);
		$this->datagrid->addAction($action2);

		// cria o modelo da DataGrid, montando sua estrutura
		$this->datagrid->createModel();

		// monta a página através de uma tabela
		$tableList = new TTable;
		$row = $tableList->addRow();
		$row->addCell($new_button);
		$row = $tableList->addRow();
		$row->addCell($this->datagrid);

		//cria barra de navegação da dbgrid
		$this->paginator = new TPaginator();
		$this->paginator->setClass($this);
		$this->paginator->setImages(array("app.images/ico_first.png","app.images/ico_prior.png","app.images/ico_next.png","app.images/ico_last.png"));
		$this->paginator->setFieldsOrder($this->FIELDS_ORDER_BY);
		$row = $tableList->addRow();
		$row->addCell($this->paginator);

		$divList->add($divSearchForm);
		$divList->add($tableList);
		parent::add($divList);
	}

	/*
	 * método onReload()
	 * Carrega a DataGrid com os objetos do banco de dados
	 */
	function onReload($url=NULL)
	{
		// inicia transação com o banco de dados
		TTransaction::open();

		//print_r($url);
		if(is_array($url))
		{
			if(isset($url['pag'])){
				$pag = $url['pag'];
			}
			if(isset($url['param'])){
				$param = $url['param'];
			}
			if(isset($param))
			{
				$param = explode(' ',$param);
				$p = $param[2];
				$p = str_replace("'",'',$p);
				$filter = new TFilter($param[0],$param[1],$p);
			}
			if(isset($url['order'])){
				$orderBy = $url['order'];
			}
			
			if(isset($url['msg']))
			{
				new TMessage($url['msg'],NULL," app.images/info.png");
			}
		}

		if(!(isset($pag)))
		{
			$pag = 1;
		}


		// instancia um repositório para Produto
		$repository = new TRepository($this->class);
		//cria um critério de seleção de dados
		$criteria = new TCriteria;
		// ordena pelo campo id


		$inicio = ($pag * $this->regPorPagina) - $this->regPorPagina;
		$limit = $inicio.','.$this->regPorPagina;
		$criteria->setProperty('limit', $limit);

		// obt�m os dados do formul�rio de buscas
		$dados = $this->form->getData();
		// verifica se o usu�rio preencheu o formul�rio
		if ($dados->valueSearch)
		{
			if($dados->comboCondition == '%')
			{
				$filter = new TFilter($dados->comboFieldSearch, 'like', "%{$dados->valueSearch}%");
			}
			else
			{
				$filter = new TFilter($dados->comboFieldSearch, $dados->comboCondition, "{$dados->valueSearch}");
			}
		}

		if($dados->comboFieldSearch){
			$this->comboFieldSearch->setValue($dados->comboFieldSearch);
		}
		if($dados->comboCondition){
			$this->comboCondition->setValue($dados->comboCondition);
		}
		if($dados->valueSearch){
			$this->valueSearch->setValue($dados->valueSearch);
		}


		if(isset($filter))
		{
			$criteria->add($filter);
		}
		
		$criteria->add(new TFilter($this->PK, ">", 0));

		if(isset($orderBy))
		{
			$criteria->setProperty('order', $orderBy);
		}
		else
		{
			$criteria->setProperty('order', $this->PK);
		}

		//Carrega os os registros que satisfazem o critério
		$registros = $repository->load($criteria);
		TTransaction::close();

		//MOTAGEM DA GRID COM OS DADOS
		$this->datagrid->clear();
		if ($registros)
		{
			foreach ($registros as $registro)
			{
				// adiciona o objeto na DataGrid
				$this->datagrid->addItem($registro);
			}
		}

		TTransaction::open();
		//PAGINAÇÃO
		$repoPaginator= new TRepository($this->class);
		$critPaginator = new TCriteria;
		if(isset($filter)){
			$critPaginator->add($filter);
			$this->paginator->setFilter($filter->dump());
		}
		if(isset($orderBy)){
			$this->paginator->setOrder($orderBy);
		}

		$regPaginator = $repoPaginator->load($critPaginator);
		$totalRegistros = count($regPaginator);
		$totalPaginas = ceil($totalRegistros/$this->regPorPagina);
		if($totalPaginas==0)
		$totalPaginas = 1;
		$this->paginator->setCountPag($totalPaginas);
		$this->paginator->setCountRecord($totalRegistros);
		$this->paginator->setPagNow($pag);

		$this->paginator->setUrl();
		$this->paginator->clear();
		$this->paginator->createNavegator();
		//FIM PAGINAÇÃO


		// finaliza a transação
		TTransaction::close();
		$this->loaded = true;
	}

	/*
	 * método onDelete()
	 * Executada quando o usuário clicar no botão excluir da datagrid
	 * Pergunta ao usuário se deseja realmente excluir um registro
	 */
	function onDelete($param)
	{
		$niveUsuario = TSession::getValue('SISPBR_PROCON_USU_NIVEL');
		if($niveUsuario == '1'){
			$this->onReload($param);
			//obtém o parâmetro $key
			$action1 = new TAction(array($this, 'Delete'));
			$key=$param['key'];
			$action1->setParameter('key', $key);

			if(isset($param['pag'])){
				$pag=$param['pag'];
				$action1->setParameter('pag', $pag);
			}

			if(isset($param['param'])){
				$par=$param['param'];
				$action1->setParameter('param', $par);
			}
			if(isset($param['order'])){
				$order=$param['order'];
				$action1->setParameter('order', $order);
			}
			// define os parâmetros de cada ação
			// exibe um diálogo ao usuário
			new TQuestion('Deseja realmente excluir o registro de código '.$key.'?', $action1, NULL," app.images/question.png");
		}
		else{
			new TMessage("Operação permitida somente para administrador.",NULL," app.images/info.png");
		}
	}

	/*
	 * método Delete()
	 * Exclui um registro
	 */
	function Delete($param)
	{

		$niveUsuario = TSession::getValue('SISPBR_PROCON_USU_NIVEL');
			
		if($niveUsuario == '1'){
			// obtém o parâmetro $key
			$key=$param['key'];

			try{
				// inicia transa��o com o banco 'pg_livro'
				TTransaction::open();
					
				// instanicia objeto Produto
				$obj = new $this->class($key);
				// deleta objeto do banco de dados
				$obj->delete();
					
				// finaliza a transa��o
				TTransaction::close();
					
				// re-carrega a datagrid
				$this->onReload($param);

				// exibe mensagem de sucesso
				new TMessage("Registro excluído com sucesso!",NULL," app.images/info.png");
			}
			catch (Exception $e){
				new TMessage('<b>Erro</b>'.'<br />'.$e->getMessage(),null,"app.images/error.png");
				TTransaction::rollback();
			}
		}
		else{
			new TMessage("Operação permitida somente para administrador.",NULL," app.images/info.png");
		}
	}

	/*
	 * método show()
	 * Executada quando o usu�rio clicar no bot�o excluir
	 */
	function show()
	{
		// se a listagem ainda n�o foi carregada
		if (!$this->loaded)
		{
			$this->onReload();
		}
		parent::show();
	}
}
?>
