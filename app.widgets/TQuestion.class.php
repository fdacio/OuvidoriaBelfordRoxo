<?php
/**
 * classe TQuestion
 * Exibe perguntas ao usuário
 */
class TQuestion
{
    /**
     * método construtor
     * instancia objeto TQuestion
     * @param $message = pergunta ao usuário
     * @param $action_yes = ação para resposta positiva
     * @param $action_no = ação para resposta negativa
     */
    function __construct($message, TAction $action_yes, TAction $action_no=NULL, $src_image="")
    {
        // instancia o painel para exibir o diálogo
        $painel = new TElement('div');
        $painel->class = "tquestion";
		
		$painel2 = new TElement('div');
        $painel2->id = "div_icon";

		$painel3 = new TElement('div');
        $painel3->id = "div_msg";

		$painel4 = new TElement('div');
        $painel4->id = "div_btn";

        $button1 = new TButtonDialog('btnsim');
		$button1->setAction($action_yes,'Sim');
		$button1->setProperty('id', 'btnDialogSim');
		
		$button2 = new TButtonDialog('btnno');
		$button2->setAction($action_no,'Não');
		$button2->setProperty('id', 'btnDialogClose');			
	
		$painel2->add(new TImage($src_image));
		$painel3->add($message);
        $painel4->add($button1);
		$painel4->add($button2);
        // cria um botão para a resposta positiva

        
        // adiciona a tabela ao painél
        $painel->add($painel2);
        $painel->add($painel3);
		$painel->add($painel4);
        // exibe o painél
        $painel->show();
    }
	
	
}
?>