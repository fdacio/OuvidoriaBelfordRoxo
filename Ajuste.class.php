<?php
function __autoload($classe)
{
	$pastas = array('app.ado', 
					'app.model');

	foreach ($pastas as $pasta)	{
		if (file_exists("{$pasta}/{$classe}.class.php")){
			include_once "{$pasta}/{$classe}.class.php";
		}
	}
}

class Ajuste {

	var $protocolo;
	var $numeros = array(	
	53,54,55,56,
	58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,
	121,
	159,
	172,
	179,
	180,
	200,
	209,210,211,
	215,
	216,
	227,
	241,
	243,
	245,
	248,
	250,
	251,
	270,
	271,
	274,
	275,
	277,278,279,280,281,282,
	295,
	297,
	299,
	300,
	303,
	305,306,307,308,309,310,311,312,313,
	315,
	317,
	319,320,321,322,323,324,325,326,327,
	329,
	330,
	332,333,334,
	336,337,338,339,
	341,342,343,344,345,
	347,348,349,350,351,352,
	355,
	357,
	358,
	360,361,362,363,364,365,366,367,368,369,
	371,372,373,374,375,376,
	378,
	379,
	381,382,383,384,
	386,387,388,389,390,391,392,393,394,395,396,397,398,399,400,401,
	403,
	404,
	406,
	407,
	409,
	412,
	413,
	415,416,417,418,419,
	421,
	423,
	425,426,427,428,429,
	431,432,433,434,435,436,436,437,438,439,
	441,
	443,444,445,446,447,448,449,450,
	452,453,454,455,456,457,458,
	460,461,462,463,464,
	465,
	466,
	467,
	479,480,481,482,483,484,485,486,487,488);

	public function execute()
	{
		foreach($this->numeros as $numero)
		{
			
			$this->protocolo = new Protocolo();
			
            $this->protocolo->PRO_NUMERO = $numero;
        	$this->protocolo->PRO_ANO = date("Y");
        	$this->protocolo->PRO_DATA = date("Y-m-d H:i:s");
        	$this->protocolo->PRO_NOME = '*';
			$this->protocolo->USU_ATENDIMENTO = 1;
			$this->protocolo->PRO_STATUS = 1;
			try	{
				TTransaction::open();
				$this->protocolo->store();
				TTransaction::close();
			}
			catch (Exception $e){
				print ('Erro: '. $e->getMessage());
				TTransaction::rollback();
			}
			
						
		}
		
	}
}

$ajuste = new Ajuste();
$ajuste->execute();
?>