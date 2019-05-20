<?php
/**
 * classe USUARIOS
 * Active Record para tabela USUARIOS
 */
class Usuario extends TRecord {
    const TABLENAME = "USUARIO";
	const PRIMARYKEY = "USU_ID";
	public $arraySituacao = array("0"=>"Inativo","1"=>"Ativo");
	
	public function get_NIVEL_USUARIO(){
     	$nivelUsuario = new NivelUsuario();
    	return $nivelUsuario->getNome($this->data['USU_NIVEL']);    	
    }
    
    public function get_USU_SITUACAO(){
    	return $this->arraySituacao[$this->data['USU_ATIVO']]; 
    }
    
}
?>