<?php
/**
 * classe TLabel
 * classe para constru��o de r�tulos de texto
 */
class TLabelField extends TField
{
    /**
     * método construtor
     * instancia o label, cria um objeto <font>
     * @param $value = Texto do Label
     */
    public function __construct($value)
    {
        // atribui o conte�do do label
        $this->setValue($value);
        
        // instancia um elemento <font>
        $this->tag = new TElement('label');
        
    }
    
    
    /**
     * método show()
     * exibe o widget na tela
     */
    public function show()
    {
        // adiciona o conte�do � tag
        $this->tag->add($this->value);
        //adiciona as classes;
        $this->tag->class = $this->getClass();
        // exibe a tag
        $this->tag->show();
    }
}
?>