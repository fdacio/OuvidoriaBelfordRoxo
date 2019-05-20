<?php
abstract class Page {
	
	//public abstract function show();
	
	public function run()
	{

		if(isset($_GET['method']))
		{
			$class = $_GET['class'];
			$method = $_GET['method'];	
		
			if($class)
			{
				$object = $class == get_class($this)?$this:new $class;
				
				if(method_exists($object, $method))
				{					
					call_user_func(array($object, $method), $_GET);
				}
			}
			else if(function_exists($this->method))
			{
				call_user_func($this->method, $_GET);
			}
		}
	}
}