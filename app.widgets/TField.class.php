<?php
/**
 * classe TField
 * classe base para construção dos widgets para formuláros
 */
abstract class TField
{
    protected $name;
    protected $size;
    protected $value;
    protected $editable;
    protected $tag;
    protected $class_css;
    
    /**
     * método construtor
     * instancia um campo do formulario
     * @param $name = nome do campo
     */
    public function __construct($name)
    {
        // define algumas características iniciais
        self::setEditable(true);
        self::setName($name);
        self::setSize(200);

        // cria uma tag HTML do tipo <input>
        $this->tag = new TElement('input');
        $this->addClass('tfield');
        
    }
    
    
    /**
     * método setName()
     * define o nome do widget
     * @param $name     = nome do widget
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * método getName()
     * retorna o nome do widget
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * método setValue()
     * define o valor de um campo
     * @param $value    = valor do campo
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    /**
     * método getValue()
     * retorna o valor de um campo
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * método setEditable()
     * define se o campo poderá ser editado
     * @param $editable = TRUE ou FALSE
     */
    public function setEditable($editable)
    {
        $this->editable= $editable;
    }
    
    /**
     * método getEditable()
     * retorna o valor da propriedade $editable
     */
    public function getEditable()
    {
    	return $this->editable;
    }
    
    /**
     * método setProperty()
     * define uma propriedade para o campo
     * @param $name = nome da propriedade
     * @param $valor = valor da propriedade
     */
    public function setProperty($name, $value)
    {
        // define uma propriedade de $this->tag
        $this->tag->$name = $value;
    }
    
    /**
     * método setSize()
     * define a largura do widget
     * @param $width = largura em pixels
     * @param $height = altura em pixels (usada em TText)
     */
    public function setSize($width, $height = NULL)
    {
        $this->size = $width;
    }
    
    /**
     * Método para obter a classe css dos fields(elementos do formulário)
     * 
     */
    public function getClass()
    {
    	if($this->class_css != '')
    		return implode(" ",$this->class_css);
    	else 
    		return '';	

    }
    
    /**
     * Método para setar classes css aos fields(elementos do formulário)
     * 
     */
    
    public function addClass($class)
    {
    	$this->class_css[] = $class;
    	
    	
    }
    
}
?>
