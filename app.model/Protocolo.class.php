<?php

class Protocolo extends TRecord
{

    const TABLENAME = "PROTOCOLO";

    const PRIMARYKEY = "PRO_ID";

    public $arrayStatus = array(
        "1" => "Aberto",
        "2" => "Em Execução",
        "3" => "Finalizado",
        "4" => "Excluído"
    );

    public function get_PRO_NUMERO_ANO()
    {
        return ($this->PRO_NUMERO != '') ? str_pad($this->PRO_NUMERO, 6, "0", STR_PAD_LEFT) . '/' . $this->PRO_ANO : '';
    }

    public function get_PRO_NUMEROANO()
    {
        return ($this->PRO_NUMERO != '') ? str_pad($this->PRO_NUMERO, 6, "0", STR_PAD_LEFT) . $this->PRO_ANO : '';
    }

    public function get_ATEND_RESULTADO()
    {
        TTransaction::open();
        $usuario = new Usuario($this->USU_RESULTADO);
        TTransaction::close();
        return $usuario->USU_NOME;
    }

    public function get_ATEND_RESULTADO_MATRICULA()
    {
        TTransaction::open();
        $usuario = new Usuario($this->USU_RESULTADO);
        TTransaction::close();
        return $usuario->USU_MATRICULA;
    }

    public function get_ATENDENTE()
    {
        TTransaction::open();
        $usuario = new Usuario($this->USU_ATENDIMENTO);
        TTransaction::close();
        return $usuario->USU_NOME;
    }

    public function get_ATENDENTE_MATRICULA()
    {
        TTransaction::open();
        $usuario = new Usuario($this->USU_ATENDIMENTO);
        TTransaction::close();
        return $usuario->USU_MATRICULA;
    }

    public function get_STATUS()
    {
        return $this->arrayStatus[$this->PRO_STATUS];
    }

    public function get_DATA()
    {
        return TFuncoes::formataDataHoraBr($this->PRO_DATA);
    }

    public function get_DATA_FINALIZACAO()
    {
        return TFuncoes::formataDataHoraBr($this->PRO_DATA_FINALIZACAO);
    }

    public function get_DATA_EXECUCAO()
    {
        return TFuncoes::formataDataHoraBr($this->PRO_DATA_EXECUCAO);
    }

    public function get_NASCIMENTO()
    {
        return TFuncoes::formataDataHoraBr($this->PRO_NASCIMENTO);
    }

    public function get_TEMPO()
    {
        return TFuncoes::timeToStr($this->PRO_TEMPO_DECORRIDO);
    }
}

?>