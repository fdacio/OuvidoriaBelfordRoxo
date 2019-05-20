<?php
class Menu extends Page{
	
	private $html;
	
	public function __construct(){
	}


	public function show(){
		if(TSession::getValue('SISPBR_OUVID_USU_ID')){
			parent::run();
			echo $this->html;
		}else{
			header('Location:?class=Login');
		}
	}
	
	public function onLoad(){
		if(TSession::getValue('SISPBR_OUVID_USU_NIVEL') != '3'){
			$this->html = file_get_contents('app.view/menu_admin.html');
		
		}else{
			$this->html = file_get_contents('app.view/menu_cliente.html');
		}
		
	}
}
?>