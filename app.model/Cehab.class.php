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
    
    public function get_ENDERECO_COMPLETO()
    {
        return $this->ENDERECO . ', ' . $this->NUMERO . ' ' . $this->COMPLEMENTO . 
            ' - ' . $this->BAIRRO; 
    }
    
    public function get_CEHAB_ESCRITURA()
    {
        return ($this->TEM_ESCRITURA == 1)?'Sim':'Não';
    }
    
    public function get_CEHAB_PROPRIETARIO()
    {
        return ($this->PROPRIETARIO == 1)?'Sim':'Não';
    }
    
}