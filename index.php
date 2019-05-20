<?php
function __autoload($classe)
{
	$pastas = array('app.widgets',
					'app.ado', 
					'app.model', 
					'app.report',
					'app.excel',
					'app.control',	
					'app.control/usuario', 
					'app.control/protocolo',
					'app.webservice');

	foreach ($pastas as $pasta)	
	{
		if (file_exists("{$pasta}/{$classe}.class.php"))
		{
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
	private $index;
	private $content;
	private $header;
	private $footer;
	private $class;
	private $method;
	private $idUsuario;
	private $nivelUsuario;
	
	/**
	 * 
	 * Método construtor
	 */
	private function __construct()
	{
		$this->index  = file_get_contents('app.view/index.html');
		$this->header = file_get_contents('app.view/header.html');
		$this->footer = file_get_contents('app.view/footer.html');
		$this->index = str_replace('#HEADER',$this->header, $this->index);
		$this->index = str_replace('#FOOTER',$this->footer, $this->index);
	}

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
	 * Método para realizar Logout no sistema, quado o usuário logado
	 * não tiver permissçao a determinda classe;
	 */
	private function doLogout()
	{
		$login = new Login();
		$login->doLogout();
	}

	/**
	 * 
	 * Método para construir o menu de login
	 */
	private function buildMenuLogin()
	{
		if($this->idUsuario)
		{
			$menuLogin = file_get_contents('app.view/menu_login.html');
			$menuLogin = str_replace('#NOME_USUARIO',TSession::getValue('SISPBR_OUVID_USU_NOME'), $menuLogin);
			$this->index = str_replace('#MENU_LOGIN',$menuLogin, $this->index);
		}
		else
		{
			$this->index = str_replace('#MENU_LOGIN',"", $this->index);
			$this->index = str_replace('#MENU_MODULO',"", $this->index);
		}
	}

	/**
	 * Classe para construir o menu dos módulos
	 */
	private function buildMenuModulo()
	{
		if($this->idUsuario)
		{
			$path = TFuncoes::getPathFile($this->class);
			$fileMenuModulo = $path.'/mmenu.html';
			$fileMenuModuloAdmin = $path.'/mmenu_admin.html';
			$existFileMenuModulo = file_exists($fileMenuModulo);
			$existFileMenuModuloAdmin = file_exists($fileMenuModuloAdmin); 
			
			if($this->niveUsuario > '1')
			{
				$menuModulo = ($existFileMenuModulo) ? file_get_contents($fileMenuModulo):'';
			}	
			else
			{
				$menuModulo = ($existFileMenuModuloAdmin) ? file_get_contents($fileMenuModuloAdmin):(($existFileMenuModulo) ? file_get_contents($fileMenuModulo):'');
			}
			
			$this->index = str_replace('#MENU_MODULO',$menuModulo, $this->index);
			
		}
		else
		{
			$this->index = str_replace('#MENU_MODULO',"", $this->index);
		}
	}
	

	/**
	 * 
	 * Método que inicializa a aplicação, equivalente ao main()
	 */
	
	public function run()
	{		
		new TSession;
		$this->idUsuario = TSession::getValue('SISPBR_OUVID_USU_ID');
		$this->niveUsuario = TSession::getValue('SISPBR_OUVID_USU_NIVEL');
			
		//Verifica se há uma sessão de cliente ou usuario admin/operador
		
		if($this->idUsuario != '')
		{
			if($_GET)
			{
				$this->class = $_GET['class'];
				$this->method = isset($_GET['method'])?$_GET['method']:'';
			}else
			{
				header('Location:?class=Menu&method=onLoad');
			}

		}else
		{
			if($_GET)
			{
				$this->class = $_GET['class'];
			}else
			{
				$this->class = 'Login';
			}
		}

		/*
		 AQUI ONDE AS CLASSES SÃO INSTANCIADAS E METODO SHOW É CHAMADO
		 PARA EXIBIR A PÁGINA. AO EXECUTAR O METODO SHOW DE PAGE E TPAGE
		 É EXECUTADO TAMBEM O METEDO PASSADO NA URL.
		 */
		
		if(class_exists($this->class))
		{
			$pagina = new $this->class;
			ob_start();
			$pagina->show();
			$this->content = ob_get_contents();
			ob_end_clean();
				
		}
		else if(function_exists($this->method))
		{
			call_user_func($this->method, $_GET);
		}
		
		$this->buildMenuLogin();
		$this->buildMenuModulo();
		echo str_replace('#CONTENT',$this->content, $this->index);

	}

}

$application = TApplication::getInstance();
$application->run();

?>