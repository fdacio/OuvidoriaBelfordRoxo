<?php
/*
 * classe ProdutosList
 * Listagem de Produtos
 */
class CehabList extends TListData
{
    private $form;//formulário de buscas
    private $datagrid;//listagem
	private $paginator;//
    private $regPorPagina = 10;//Quantidade de registros por página.
	private $loaded;
	private $class = "Cehab";//Classe que representa o modelo de dados no mapeamento objeto relacional
	private $classFormInsert = "CehabForm";//Classe que representa o formulário de Alteração dados;
	private $classFormUpdate = "CehabForm";//Classe que representa o formulário de Alteração dados;
    private $labelForm = "CEHAB";
	private $labelBotaoNovo = "Novo Registro";
	
	/*
	* Campo que representa a chave primaria da tabela;
	*/
	private $PK = "ID"; 
	
    /*
	* Campos para montar o combo de pesquisa na listagem.										  							  
	*/
	private $FIELDS_SEARCH = array("CPF"=>"CPF",
									"NOME"=>"Nome");
									   
	/*
	* Nomes dos campos da tabela do banco que iram compor a grid da listagem 
	*/
	private $FIELDS_GRID = array("ID",
	                             "CPF",
	                             "NOME",
	                             "ENDERECO_COMPLETO",
	    "CEHAB_ESCRITURA","CEHAB_PROPRIETARIO");
	/*
	* Label dos cabeçalho da grid							
	*/
	private $FIELDS_GRID_LABEL = array("Código",
									   "CPF",
									   "Nome",
									   "Endereço",									   
									   "Escritura","Proprietário"); 
	/*
	*  Tamanho das colunas na grid								   
	*/
	private $FIELDS_GRID_LENGTH = array("5%","20%","25%","30%","10%","10%");
	
	/*
	* Alinhamento das colunas na grid
	*/
	private $FIELDS_GRID_ALIGN = array("left","left","left","left","center","center");



	private $FIELDS_ORDER_BY = array("ID"=>"Código",
	                             "NOME"=>"Nome");

    /*
     * método construtor
     * Cria a página, o formulário de buscas e a listagem
     */
    public function __construct()
    {
		parent::setClass($this->class);
    	parent::setClassForm($this->classFormInsert);
		parent::setClassFormUpdate($this->classFormUpdate);
		parent::setLabelForm($this->labelForm);
		parent::setLabelNewButton($this->labelBotaoNovo);
		parent::setRegistrosPorPagina($this->regPorPagina);
		parent::setPK($this->PK);
		parent::setFieldsSearch($this->FIELDS_SEARCH);
		parent::setFieldsGrid($this->FIELDS_GRID);
		parent::setFieldsGridLabel($this->FIELDS_GRID_LABEL);
		parent::setFieldsGridLength($this->FIELDS_GRID_LENGTH);
		parent::setFieldsGridAlign($this->FIELDS_GRID_ALIGN);
		parent::setFieldsOrderBy($this->FIELDS_ORDER_BY);
		
    	parent::__construct();
    }

}
?>
