<?php
abstract class TFormDataUser extends TFormData {
	
	/*
	* método onSave
	* Executado quando o usuário clicar no botão salvar
	*/
	function onSave()
	{
		/*
		 * Instancia o objeto a ser salvo através do formulário de edição
		 */
		$classUsuarios = $this->getClass();
		$form = $this->getForm();
		$obj = $form->getData($classUsuarios);		
		
		/*
		 *  Instancia um objeto de validação do dados;
		 */
		$validate = new TValidate($this);
		$validate->validate();
		
		if($validate->getMessage() == '')
		{
			if($obj->USU_ID != '')
			{
				if($_POST['ALTERAR_SENHA'] != '')
				{
					if($_POST['USU_SENHA']== '')
					{
						$form->setData($obj);
						new TMessage('Informe a Senha!',null,"app.images/error.png");	
					}
					else if($_POST['USU_SENHA'] == $_POST['USU_CONFIRMAR_SENHA'])
					{
						$obj->USU_SENHA = TFuncoes::passwordMD5($_POST['USU_SENHA']);
						try
						{
							TTransaction::open();
							$obj->store();
							TTransaction::close();
							$form->setData($obj);
							$action1 = new TAction(array($this, 'goList'));
							$pag = $_GET['pag'];
							$action1->setParameter('pag',$pag);
							new TMessage('Dados salvos com sucesso!',$action1,"app.images/info.png");
						}
						catch (Exception $e)
						{
							$form->setData($obj);
							new TMessage('<b>Erro</b>'.'<br />'.$e->getMessage(),null,"app.images/error.png");
			   				TTransaction::rollback();
						}

					
					}
					else
					{
						$form->setData($obj);
						new TMessage('Confirmação de Senha Inválida!',null,"app.images/error.png");		
						
					}
				}
				else 
				{
					try
					{
						TTransaction::open();
						$obj->store();
						TTransaction::close();
						$form->setData($obj);
						$action1 = new TAction(array($this, 'goList'));
						$pag = $_GET['pag'];
						$action1->setParameter('pag',$pag);
						new TMessage('Dados salvos com sucesso!',$action1,"app.images/info.png");
					}
					catch (Exception $e)
					{
						$form->setData($obj);
						new TMessage('<b>Erro</b>'.'<br />'.$e->getMessage(),null,"app.images/error.png");
						TTransaction::rollback();
					}
				}
			}
			else 
			{
				if($_POST['USU_SENHA'] == $_POST['USU_CONFIRMAR_SENHA'])
				{
					$obj->USU_SENHA = TFuncoes::passwordMD5($_POST['USU_SENHA']);
					try
					{
						TTransaction::open();
						$obj->store();
						TTransaction::close();
						$form->setData($obj);
						$action1 = new TAction(array($this, 'goList'));
						$pag = $_GET['pag'];
						$action1->setParameter('pag',$pag);
						new TMessage('Dados salvos com sucesso!',$action1,"app.images/info.png");
					}
					catch (Exception $e)
					{
						$form->setData($obj);
						new TMessage('<b>Erro</b>'.'<br />'.$e->getMessage(),null,"app.images/error.png");
						TTransaction::rollback();
					}
				}
				else
				{
					$form->setData($obj);
					new TMessage('Confirmação de Senha Inválida',null,"app.images/error.png");
				}
			}
		}
		else
		{
			$form->setData($obj);
			new TMessage($validate->getMessage(),null,"app.images/error.png");		
		}	
	}
		
}

?>