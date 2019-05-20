<?php
/*
 * classe NIVEL DE USUARIO
 * Active Record para tabela NIVEL DE USUARIO
 */
class NivelUsuario{

	private $niveis = array("1"=>"Administrador","2"=>"Operador");
    
	public function __construct(){
    }
	
    public function getNome($value){
    	return $this->niveis[$value];
    }
    public function getNiveis(){
    	return $this->niveis;
    }
    
    
}
?>