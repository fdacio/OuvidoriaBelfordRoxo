<?php
/*
 * classe ProdutosList
 * Listagem de Produtos
 */
class UsuariosList extends TListData
{
    private $form;//formulário de buscas
    private $datagrid;//listagem
	private $paginator;//
    private $regPorPagina = 10;//Quantidade de registros por página.
	private $loaded;
	private $class = "Usuario";//Classe que representa o modelo de dados no mapeamento objeto relacional
	private $classFormInsert = "UsuariosFormInsert";//Classe que representa o formulário de Alteração dados;
	private $classFormUpdate = "UsuariosFormUpdate";//Classe que representa o formulário de Alteração dados;
    private $labelForm = "Usuários";
	private $labelBotaoNovo = "Novo Usuário";
	
	/*
	* Campo que representa a chave primaria da tabela;
	*/
	private $PK = "USU_ID"; 
	
    /*
	* Campos para montar o combo de pesquisa na listagem.										  							  
	*/
	private $FIELDS_SEARCH = array("USU_NOME"=>"Nome",
									"USU_EMAIL"=>"e-Mail");
									   
	/*
	* Nomes dos campos da tabela do banco que iram compor a grid da listagem 
	*/
	private $FIELDS_GRID = array("USU_ID",
	                             "USU_NOME",
	                             "USU_MATRICULA",
	                             "USU_EMAIL",
								  
								 "NIVEL_USUARIO",
								 "USU_SITUACAO");
	/*
	* Label dos cabeçalho da grid							
	*/
	private $FIELDS_GRID_LABEL = array("Código",
									   "Nome",
									   "Matrícula",
									   "e-Mail",									   
									   "Nível",
									   "Situação"); 
	/*
	*  Tamanho das colunas na grid								   
	*/
	private $FIELDS_GRID_LENGTH = array("5%","20%","20%","35%","15%","15%");
	
	/*
	* Alinhamento das colunas na grid
	*/
	private $FIELDS_GRID_ALIGN = array("left","left","left","left","left","center");



	private $FIELDS_ORDER_BY = array("USU_ID"=>"Código",
	                             "USU_NOME"=>"Nome",
	                             "USU_EMAIL"=>"e-Mail",
								 "USU_Nivel"=>"Nível",
								 "USU_ATIVO"=>"Situação");

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
