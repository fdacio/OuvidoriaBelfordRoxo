<?php
function __autoload($classe)
{
	$pastas = array
	(
	'../app.ado', 
	'../app.model',
	'../app.control',
	'../ws'
	);

	foreach ($pastas as $pasta)	{
		if (file_exists("{$pasta}/{$classe}.class.php")){
			include_once "{$pasta}/{$classe}.class.php";
		}
	}
}

/**
 *
 * Classe Controller
 * @author fdacio
 *
 */
class TApplication
{

	private static $instance;
	private $class;
	private $method;
	private $contentJson;

	/**
	 *
	 * Método construtor
	 */
	private function __construct(){}

	/**
	 *
	 * Método Singleton para obter instancia unica de TApplication
	 */
	public static function getInstance()
	{

		if(empty(self::$instance)){
			self::$instance = new TApplication();
		}
		return self::$instance;

	}

	/**
	 *
	 * Método que inicializa a aplicação, equivalente ao main()
	 */

	public function run()
	{

		if($_GET){
			
			$this->class  = isset($_GET['class'])  ? $_GET['class']  : '';
			$this->method = isset($_GET['method']) ? $_GET['method'] : '';

			if(class_exists($this->class)){
				$object = $this->class == get_class($this)?$this:new $this->class;
				if(method_exists($object, $this->method)){					
					$this->contentJson = call_user_func(array($object, $this->method), $_GET);
					echo $this->contentJson;
				}else{
					echo "ERROR: Método Inválido";
				}
			}else{
				echo "ERROR: Classe Inválida";		
			}			
						
		}

	}

}

$application = TApplication::getInstance();
$application->run();

?>