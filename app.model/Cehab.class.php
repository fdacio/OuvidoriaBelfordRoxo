<?php
class Cehab extends TRecord {
    
    const TABLENAME = "CEHAB";
    const PRIMARYKEY = "ID";
    
    public $arraySituacao = array(
        "1" => "Ativa",
        "2" => "Inativo"
    );
    
    public function get_CEHAB_CRIADO()
    {
        return TFuncoes::formataDataHoraBr($this->CRIADO);
    }
    
    public function get_CEHAB_ALTERADO()
    {
        return TFuncoes::formataDataHoraBr($this->ALTERADO);
    }
    
    public function get_CEHAB_SITUACAO()
    {
        return $this->arraySituacao[$this->SITUACAO];
    }
    
}