<?php
class ProtocoloExecucaoList extends TPage{
	
	private $form;//formulário de buscas
    private $datagrid;//listagem
	private $paginator;//
	private $loaded;
    private $regPorPagina = 10;//Quantidade de registros por página.
	private $class = "Protocolo";//Classe que representa o modelo de dados no mapeamento objeto relacional
	private $classForm      = "FinalizarProtocoloForm";//Classe que representa o formulário de Alteração dados;
	private $classFormAbrir = "AbrirProtocoloForm";
	private $comboFieldSearch;
	private $comboCondition;
	private $valueSearch;
	
	
	/*
	* Campo que representa a chave primaria da tabela;
	*/
	private $PK = "PRO_ID"; 
	
    /*
	* Campos para montar o combo de pesquisa na listagem.										  							  
	*/
	private $FIELDS_SEARCH = array("PRO.PRO_NOME"=>"Nome",
								   "PRO.PRO_NUMERO"=>"Número",
									"PRO.PRO_ENDERECO"=>"Endereço",
									"PRO.PRO_BAIRRO"=>"Bairro",
									"PRO.PRO_CEP"=>"CEP");
	
									   
	/*
	* Nomes dos campos da tabela do banco que iram compor a grid da listagem 
	*/
	private $FIELDS_GRID = array("PRO_NUMERO_ANO",								 	
								 "DATA",
								 "PRO_NOME",
								 "PRO_ENDERECO",
								 "ATENDENTE",
								 "DATA_EXECUCAO");
	/*
	* Label dos cabeçalho da grid							
	*/
	private $FIELDS_GRID_LABEL = array("Número",
									   "Data",
									   "Nome",
									   "Endereço",	
									   "Atendente",
									   "Data Execução"); 
	/*
	*  Tamanho das colunas na grid								   
	*/
	private $FIELDS_GRID_LENGTH = array("10%","15%","20%","20%","20%","15%");
	
	/*
	* Alinhamento das colunas na grid
	*/
	private $FIELDS_GRID_ALIGN = array("left","left","left","left","left","left");


	private $FIELDS_ORDER_BY = array("PRO.PRO_NUMERO,PRO.PRO_ANO"=>"Número","PRO.PRO_NOME"=>"Nome");

	
	
    public function __construct(){
    	
    	parent::__construct();
		
    	$divList = new TElement('div');
		$divList->class='div_list';
		$label1 = new TElement('h2');
		$label1->add('Protocolos em Execução');

		$divSearchForm = new TElement('div');
		$divSearchForm->class='div_searchform div_searchform_os';

		$this->form = new TForm('form_busca');
		$fieldset = new TElement('fieldset');
		$legend = new TElement('legend');
		$legend->add('Consulta de Protocolos');
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
		$this->form->add($fieldset);
		// define quais são os campos do formulário
		$this->form->setFields(array($this->comboFieldSearch, $this->comboCondition, $this->valueSearch, $find_button));

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
		
		$formFinalizar = new $this->classForm;
		$formAbrir     = new $this->classFormAbrir;
	
		$action1 = new TDataGridActionExtra(array($formFinalizar, 'onFinalizar'));
		$action1->setLabel('Finalizar');
		$action1->setField($this->PK);
		
		$action2 = new TDataGridActionExtra(array($formAbrir, 'onEdit'));
		$action2->setLabel('Alterar');
		$action2->setField($this->PK);
		
		
		$action4 = new TDataGridAction(array($this, 'gerarPdfDemo'));
		$action4->setImage('app.images/pdf.png');
		$action4->setField($this->PK);
		
	
		if(isset($_GET['pag']))	{
			$action1->setParameter('pag',$_GET['pag']);
		}
		if(isset($_GET['param'])){
			$action1->setParameter('param',$_GET['param']);
		}

		if(isset($_GET['order'])){
			$action1->setParameter('order',$_GET['order']);
		}

		// adiciona as ações à DataGrid
		$this->datagrid->addActionExtra($action1);
		$this->datagrid->addActionExtra($action2);
		$this->datagrid->addAction($action4);
		$this->datagrid->class='tdatagrid_table';
		// cria o modelo da DataGrid, montando sua estrutura
		$this->datagrid->createModel();

		// monta a página através de uma tabela
		$tableList = new TTable;
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
		$divList->add($label1);
		$divList->add($tableList);
		parent::add($divList);
    }
	
    
	/*
	 * método onReload()
	 * Carrega a DataGrid com os objetos do banco de dados
	 */
	function onReload($url=NULL){
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
		}

		if(!isset($pag)){
			$pag = 1;
		}


		// instancia um repositório para Produto
		$repository = new TRepository('Protocolo');
		//cria um critério de seleção de dados
		$criteria = new TCriteria;
		// ordena pelo campo id


		$inicio = ($pag * $this->regPorPagina) - $this->regPorPagina;
		$limit = $inicio.','.$this->regPorPagina;
		$criteria->setProperty('limit', $limit);

		// obtém os dados do formulário de buscas
		$dados = $this->form->getData();
		// verifica se o usuário preencheu o formulário
		if ($dados->valueSearch){
			if($dados->comboCondition == '%'){
				$filter = new TFilter($dados->comboFieldSearch, 'like', "%{$dados->valueSearch}%");
			}
			else{
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
				
		if(isset($filter)){
			$criteria->add($filter);
		}

		if(isset($orderBy)){
			$criteria->setProperty('order', $orderBy. " DESC");
		}
		else{
			$criteria->setProperty('order', $this->PK . " DESC");
		}

		
		$criteria->add(new TFilter('PRO.PRO_STATUS','=','2'));
				
			
		$sql = new TSqlSelect();
		$sql->addColumn('PRO.*');
		$sql->setEntity('PROTOCOLO PRO INNER JOIN USUARIO USU ON PRO.USU_ATENDIMENTO = USU.USU_ID ');
		$sql->setCriteria($criteria);
		
		//echo $sql->getInstruction();
		// os os registros que satisfazem o critério
		$registros = $repository->loadFromSql($sql);
		TTransaction::close();

		//MOTAGEM DA GRID COM OS DADOS
		$this->datagrid->clear();
		if ($registros){
			foreach ($registros as $registro){
				// adiciona o objeto na DataGrid
				$this->datagrid->addItem($registro);
			}
		}

		//PAGINAÇÃO
		$sql = new TSqlSelect();
		$sql->addColumn('PRO.*');
		$sql->setEntity('PROTOCOLO PRO INNER JOIN USUARIO USU ON PRO.USU_ATENDIMENTO = USU.USU_ID');
		
		TTransaction::open();		
		$repoPaginator= new TRepository('Protocolo');
		$critPaginator = new TCriteria;
		$critPaginator->add(new TFilter('PRO.PRO_STATUS','=','2'));
		if(isset($filter)){
			$critPaginator->add($filter);
			$this->paginator->setFilter($filter->dump());
		}
		if(isset($orderBy)){
			$this->paginator->setOrder($orderBy);
		}
		$sql->setCriteria($critPaginator);
		$regPaginator = $repoPaginator->loadFromSql($sql);
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
	
	public function gerarPdfDemo(){
		
		try{
			$idProtocolo = $_GET['key'];
			TTransaction::open();
			$protocolo = new Protocolo($idProtocolo);
			TTransaction::close();
			$demo = new DemoProtocoloReport();
			$demo->setContent($protocolo);
			$nameFile = "Protocolo".$protocolo->PRO_NUMEROANO.".pdf";
			$demo->download($nameFile);
		}catch (Exception $e){
			new TMessage('Erro ao gerar pdf do protocolo: '. $e->getMessage(), NULL," app.images/error.png");
			TTransaction::rollback();
		}
			
		
	}
}
?>