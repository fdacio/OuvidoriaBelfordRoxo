<?php
/**
 * classe TAction
 * encapsula uma a��o
 */
class TAction
{
    private $action;
    private $param;
    
    /**
     * método __construct()
     * instancia uma nova ação
     * @param $action = método a ser executado
     */
    public function __construct($action)
    {
        $this->action = $action;
    }
    
    /**
     * método setParameter()
     * acrescenta um parâmetro ao método a ser executdao
     * @param $param = nome do parâmetro
     * @param $value = valor do parâmetro
     */
    public function setParameter($param, $value)
    {
        $this->param[$param] = $value;
    }
    
    /**
     * método serialize()
     * transforma a ação em uma string do tipo URL
     */
    public function serialize()
    {
        // verifica se a a��o � um m�todo
        if (is_array($this->action))
        {
            // obt�m o nome da classe
            $url['class'] = get_class($this->action[0]);
            // obt�m o nome do m�todo
            $url['method'] = $this->action[1];
        }
        else if (is_string($this->action)) // � uma string
        {
            // obt�m o nome da fun��o
            $url['method'] = $this->action;
        }
        // verifica se h� par�metros
        if ($this->param)
        {
            $url = array_merge($url, $this->param);
        }
        // monta a URL
        return '?' . http_build_query($url);
    }
}
?>