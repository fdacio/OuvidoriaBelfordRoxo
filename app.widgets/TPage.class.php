<?php
/**
 * classe TPage
 * classe para controle do fluxo de execu��o
 */
class TPage extends TElement
{
    /**
     * método __construct()
     */
    public function __construct() 
    {
        // define o elemento que irá representar
    	parent::__construct('div');
    	parent::__set('id','container');
    	parent::add("");
    }
    
    /**
     * método show()
     * exibe o conteúdo da página
     */
    public function show() {
    	if(TSession::getValue('SISPBR_OUVID_USU_ID')){
			$this->run();
   			parent::show();
    	}else{
    		header('Location:?class=Login');
    	}
    }
    
    /**
     * método run()
     * executa determinado método de acordo com os parâmetros recebidos
     */
    public function run()
    {
        if ($_GET)
        {
            $class = isset($_GET['class']) ? $_GET['class'] : NULL;
            $method = isset($_GET['method']) ? $_GET['method'] : NULL;
            if ($class)
            {
                $object = $class == get_class($this) ? $this : new $class;
				
                if (method_exists($object, $method))
                {
                    
                	call_user_func(array($object, $method), $_GET);
                }
            }
            else if (function_exists($method))
            {
                
            	call_user_func($method, $_GET);
            }
        }
    }
}
?>