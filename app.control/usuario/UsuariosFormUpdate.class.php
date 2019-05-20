<?php
/*
 * classe ContribuintesForm
 * Formulário de Cadastro de Contribuintes
 */
class UsuariosFormUpdate extends TFormData
{
    private $nameForm = "frmUsuarios";
	private $labelForm = "Usuário";
	/*
	* Classe que representa o modelo de dados no mapeamento objeto relacional
	*/
    private $class = "Usuario";
	/*
	* Classe que representa a listagem 
	*/
    private $classList = "UsuariosList";
	/*
	* Campo que representa a chave primaria da tabela;
	*/
	private $PK = "USU_ID"; 
	/*
	* Campos para o formulário de adição e alteração dos dados
	*
	* O subarray deve seguir a seguinte sequencia:
	* (Rótulo do Campo, Tamanho do Campo, Editável(true/false),Tipo de Elemento(ver tipos abaixo), array para o tipo TCombo, Mascara para o campo quando for o caso);
	* Tipos de Elementos que representam os campos
     * 1 => TEntry (input tipo text)
     * 2 => TPassword (input tipo password)
     * 3 => TFile (input tipo file)
     * 4 => THidden (input tipo hidden)
     * 5 => TCombo (lista de seleção <select>)
     * 6 => TText (área de texto <textarea>)
     * 7 => TCheckButton (input do tipo checkbox)
     * 8 => TCheckGroup (representa um grupo de TCheckButton)
     * 9 => TRadioButton (input type radio)
     * 10 => TRadioGroup (representa um grupo de TRadioButton) 
Telefone
	*/
	
	private $INPUTS_FORM = array("USU_ID"=>array("Código","80","false","TEntry",""), 
								 "USU_NOME"=>array("Nome","350","true","TEntry",""),
								 "USU_MATRICULA"=>array("Nome","350","true","TEntry",""),
								 "USU_EMAIL"=>array("e-Mail","350","true","TEntry",""),
								 "USU_NIVEL"=>array("Nível","350","true","TCombo",""),
								 "USU_ATIVO"=>array("Situação","350","true","TRadioGroup","")	); 
	
	private $FIELDS_TABLE = array("USU_ID", 
								 "USU_NOME",
								 "USU_MATRICULA",
								 "USU_EMAIL", 
								 "USU_NIVEL",
								 "USU_ATIVO"); 
	

	/*
	* Campos de preenchimento origatório no formulário.
	*/							  
	private $FIELDS_FORM_REQUERID = array("USU_NOME"=>"Nome",
								 		  "USU_EMAIL"=>"e-Mail",
										  "USU_MATRICULA"=>"Matrícula",
										  "USU_NIVEL"=>"Nível",
										  "USU_ATIVO"=>"Situação"); 
		
	
	/*Telefone
	* Campos que o valor devem ser validados Ex.: CPF/CNPJ, Datas, Númericos 
	*/							  
	private $FIELDS_FORM_VALIDATE_INFO = array("USU_EMAIL"=>array("e-Mail","EMAIL"));
	
	/*
	* Campos que o valor tem que ser único na tabela.
	*/							  
	private $FIELDS_FORM_UNIQUE = array("USU_EMAIL"=>"e-Mail");
    
    
    /*
     * método construtor
     * Cria a página e o formulário de cadastro
     */
    function __construct()
    {
      	parent::setClass($this->class);
       	parent::setClassList($this->classList);
       	parent::setNameForm($this->nameForm);
       	parent::setLabelForm($this->labelForm);
        parent::setPK($this->PK);
        
       
        $nivelUsuario = new NivelUsuario();
        $niveis = $nivelUsuario->getNiveis();
        $this->INPUTS_FORM['USU_NIVEL'][4] = $niveis;
        
        $this->INPUTS_FORM['USU_ATIVO'][4] = array("1"=>"Ativo","0"=>"Inativo");
       	
        parent::setInputsForm($this->INPUTS_FORM);
        parent::setFieldsTable($this->FIELDS_TABLE);
        parent::setFieldsFormRequerid($this->FIELDS_FORM_REQUERID);
       	parent::setFieldsFormValidateInfo($this->FIELDS_FORM_VALIDATE_INFO);
       	parent::setFieldsFormUnique($this->FIELDS_FORM_UNIQUE);
    	parent::__construct();
    	
    }
}
?>
