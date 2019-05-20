<?php
/**
 * classe TMessage
 * exibe mensagens ao usu�rio
 */
class TMessage
{
    /**
     * m�todo construtor
     * instancia objeto TMessage
     * @param $type      = tipo de mensagem (info, error)
     * @param $message = mensagem ao usu�rio
     */
    public function __construct($message, TAction $action_ok=null, $src_image="")
    {


        // instancia o painel para exibir o di�logo
        $painel = new TElement('div');
        $painel->class = "tmessage";
		
		$painel2 = new TElement('div');
        $painel2->id = "div_icon";

		$painel3 = new TElement('div');
        $painel3->id = "div_msg";

		$painel4 = new TElement('div');
        $painel4->id = "div_btn";

        $button1 = new TButtonDialog('btnDialogClose');
		$button1->setAction($action_ok,'OK');
		$button1->setProperty('id','btnDialogClose');
		
		$painel2->add(new TImage($src_image));
		$painel3->add($message);
        $painel4->add($button1);
        // cria um bot�o para a resposta positiva

        
        // adiciona a tabela ao pain�l
        $painel->add($painel2);
        $painel->add($painel3);
		$painel->add($painel4);
        // exibe o pain�l
        $painel->show();
    }
}
?>