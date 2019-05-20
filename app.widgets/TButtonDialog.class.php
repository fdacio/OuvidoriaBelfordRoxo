<?php
/* classe TButton
 * responsável por exibir um botão
 */
class TButtonDialog extends TField
{
    private $label;
	private $action;
    
    /**
     * método setAction
     * define a ação do botão (função a ser executada)
     * @param $action = ação do botão
     * @param $label    = rótulo do botão
     */
    public function setAction($action, $label)
    {
        $this->action = $action;
        $this->label = $label;
    }
    
    /**
    * método show()
    * exibe o botão
    */
    public function show()
    {
        
        // define as propriedades do botão
        $this->tag->name    = $this->name;    // nome da TAG
        $this->tag->type    = 'button';       // tipo de input
        $this->tag->value   = $this->label;   // rótulo do botão
        // se o campo não é editável
        if (!parent::getEditable())
        {
            $this->tag->disabled = "1";
            $this->addClass('tfield_disabled'); // classe CSS
        }
        //define a acao do botao
        if($this->action !=null)
        {
    		$url = $this->action->serialize();
    		$this->tag->onclick = 'javascript:location='."'".$url."'";
        }
        // exibe o botão
        $this->tag->class = $this->getClass();
        $this->tag->show();
    }
}
?>