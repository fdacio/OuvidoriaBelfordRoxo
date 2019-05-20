<?php
/*
 * classe ProdutosList
 * Listagem de Produtos
 */
class ProtocoloList extends TListData
{
    private $form;//formulário de buscas
    private $datagrid;//listagem
	private $paginator;//
    private $regPorPagina = 10;//Quantidade de registros por página.
	private $loaded;
	private $class = "Protocolo";//Classe que representa o modelo de dados no mapeamento objeto relacional
	private $classFormInsert = "";
	private $classFormUpdate = "ProtocoloForm";//Classe que representa o formulário de Alteração dados;
    private $labelForm = "Protocolos";
	private $labelBotaoNovo = "";
	
	/*
	* Campo que representa a chave primaria da tabela;
	*/
	private $PK = "PRO_ID"; 
	
    /*
	* Campos para montar o combo de pesquisa na listagem.										  							  
	*/
	private $FIELDS_SEARCH = array("PRO.PRO_NOME"=>"Nome",
								   "PRO.PRO_NUMERO"=>"Número");
									   
	/*
	* Nomes dos campos da tabela do banco que iram compor a grid da listagem 
	*/
	private $FIELDS_GRID = array("PRO_NUMERO_ANO",								 	
								 "DATA",
								 "PRO_NOME",
								 "PRO_ENDERECO",
								 "ATENDENTE","STATUS");
	/*
	* Label dos cabeçalho da grid							
	*/
	private $FIELDS_GRID_LABEL = array("Número",
									   "Data",
									   "Nome",
									   "Endereço",	
									   "Atendente","Status"); 
	/*
	*  Tamanho das colunas na grid								   
	*/
	private $FIELDS_GRID_LENGTH = array("10%","15%","25%","20%","20%","10%");
	
	/*
	* Alinhamento das colunas na grid
	*/
	private $FIELDS_GRID_ALIGN = array("left","left","left","left","left","left");


	private $FIELDS_ORDER_BY = array("PRO_NUMERO,PRO_ANO"=>"Número","PRO_NOME"=>"Nome");
	
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
