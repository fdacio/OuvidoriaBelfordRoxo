<?php
class TFuncoes{
	/**
	 *
	 * Formata as datas no formato dd/mm/yyyy para yyyy-mm-dd
	 * @param $data
	 */
	public static function formataDataUSA($data)
	{
		if($data!='')
		{
			$dia = substr($data, 0,2);
			$mes = substr($data, 3,2);
			$ano = substr($data, 6,4);
			$data = $ano.'-'.$mes.'-'.$dia;
		}
		return $data;
	}

	/**
	 *
	 * Formata as datas no formato yyyy-mm-dd para dd/mm/yyyy
	 * @param $data
	 */
	public static function formataDataBr($data)
	{
		if($data != '')
		{
			$ano = substr($data, 0,4);
			$mes = substr($data, 5,2);
			$dia = substr($data, 8,2);
			$data = $dia.'/'.$mes.'/'.$ano;
				
		}
		return $data;
	}

	/**
	 *
	 * Formata as datas no formato yyyy-mm-dd hh:mm:ss para dd/mm/yyyy hh:mm:ss
	 * @param $data
	 */
	public static function formataDataHoraBr($data)
	{
		if($data != '')
		{
			$ano = substr($data, 0,4);
			$mes = substr($data, 5,2);
			$dia = substr($data, 8,2);
			$time = substr($data, 11,8);

			$data = $dia.'/'.$mes.'/'.$ano ." " .$time;
				
		}
		return $data;
	}

	/**
	 *
	 * Formata os numero no formato 9.999.999,99 para 9999999.99
	 * @param $data
	 */
	public static function formataNumeroDecimalUSA($numero)
	{
		$numero = str_replace('.', '',$numero);
		$numero = str_replace(',', '.',$numero);
		return $numero;
	}

	/**
	 *
	 * Formata os numero no formato 9,999,999.99 para 9999999,99
	 * @param $data
	 */
	public static function formataNumeroDecimalBr($numero)
	{
		$numero = str_replace(',', '',$numero);
		$numero = str_replace('.', ',',$numero);
		return $numero;
	}


	/**
	 *
	 * Formata os valores monetario USA em BR
	 * @param $data
	 */
	public static function formataDinheiroBr($valor)
	{
		$valor = number_format($valor,2,',','.');
		return $valor;
	}

	/**
	 *
	 * funcoes para a formatacao de cpf e cnpj do contribuinte.
	 * @param $cpf_cnpj
	 */
	public static function formataCPFCNPJ($cpf_cnpj)
	{
		if(strlen($cpf_cnpj)==11)
		{
			$cpf_cnpj = substr($cpf_cnpj, 0,3).'.'.substr($cpf_cnpj, 3,3).'.'.substr($cpf_cnpj, 6,3).'-'.substr($cpf_cnpj, 9,2);
		}
		else if(strlen($cpf_cnpj)==14)
		{
			$cpf_cnpj = substr($cpf_cnpj, 0,2).'.'.substr($cpf_cnpj, 2,3).'.'.substr($cpf_cnpj, 5,3).'/'.substr($cpf_cnpj, 8,4).'-'.substr($cpf_cnpj, 12,2);
		}
		return $cpf_cnpj;
	}

	public static function desformataCPFCNPJ($cpf_cnpj)
	{
		return str_replace(array('/', '-', '.'), '', $cpf_cnpj);
	}

	/**
	 *
	 * Funcao que validar um CPF ou um CNPJ
	 * @param string $cpf_cnpj
	 */
	private function check_fake($string, $length)
	{
		for($i = 0; $i <= 9; $i++)
		{
			$fake = str_pad('', $length, $i);
			if($string === $fake)
			return TRUE;
		}
	}

	public static function validaCPFCNPJ($cpf_cnpj)
	{

		//VALIDAR CPF
		if(strlen($cpf_cnpj)==11)
		{
			if(self::check_fake($cpf_cnpj, 11))
			{
				return FALSE;
			}
			else
			{
				$sub_cpf = substr($cpf_cnpj, 0, 9);
				for($i = 0; $i <= 9; $i++)
				{
					$dv += ($sub_cpf[$i] * (10-$i));
				}
				if($dv == 0)
				return FALSE;
					
				$dv = 11 - ($dv % 11);
				if($dv > 9)
				$dv = 0;
				if($cpf_cnpj[9] != $dv)
				return FALSE;

				$dv *= 2;
				for($i = 0; $i <= 9; $i++)
				{
					$dv += ($sub_cpf[$i] * (11-$i));
				}
				$dv = 11 - ($dv % 11);
				if($dv > 9)
				$dv = 0;
				if($cpf_cnpj[10] != $dv)
				return FALSE;
				return TRUE;
			}
		}
		// VALIDAR CNPJ
		else if(strlen($cpf_cnpj)==14){
				

			if(self::check_fake($cpf_cnpj, 14))
			return FALSE;
			else{
				$rev_cnpj = strrev(substr($cpf_cnpj, 0, 12));
				for($i = 0; $i <= 11; $i++) {
					$i == 0 ? $multiplier = 2 : $multiplier;
					$i == 8 ? $multiplier = 2 : $multiplier;
					$multiply = ($rev_cnpj[$i] * $multiplier);
					$sum = $sum + $multiply;
					$multiplier++;
				}
				$rest = $sum % 11;
				if($rest == 0 || $rest == 1)
				$dv1 = 0;
				else
				$dv1 = 11 - $rest;

				$sub_cnpj = substr($cpf_cnpj, 0, 12);
				$rev_cnpj = strrev($sub_cnpj.$dv1);
				unset($sum);
				for($i = 0; $i <= 12; $i++) {

					$i == 0 ? $multiplier = 2 : $multiplier;
					$i == 8 ? $multiplier = 2 : $multiplier;
					$multiply = ($rev_cnpj[$i] * $multiplier);
					$sum = $sum + $multiply;
					$multiplier++;
				}
				$rest = $sum % 11;
				if($rest == 0 || $rest == 1)
				$dv2 = 0;
				else
				$dv2 = 11 - $rest;

				if($dv1 == $cpf_cnpj[12] && $dv2 == $cpf_cnpj[13])
				return TRUE;
				else
				return FALSE;
			}
				
				
		}
		else{
			return FALSE;
		}
	}

	/**
	 *
	 * Funcao que validar uma data no formato dd/mm/yyyy
	 * @param string $data
	 */
	public static function validaData($data)
	{
		$dia = substr($data, 0,2);
		$mes = substr($data, 3,2);
		$ano = substr($data, 6,4);
		return checkdate($mes, $dia, $ano);
	}


	/**
	 * 
	 * Função para validar se um período é válido.
	 * Um período é considerado válido, quando a $data1 é MENOR OU IGUAL que $data2
	 * @param $data1
	 * @param $data2
	 */
	public static function validaPeriodo($data1, $data2){
		$timeZone = new DateTimeZone('UTC');
		/** Assumido que $data1 e $data2 estao em formato dia/mes/ano */
		$data1 = DateTime::createFromFormat ('d/m/Y', $data1, $timeZone);
		$data2 = DateTime::createFromFormat ('d/m/Y', $data2, $timeZone);
		
		if($data1 <= $data2){
			return TRUE;
		}else{
			return FALSE;
		}

	}

	/**
	 *
	 * Funcao que verifica se um informa��o armazenada em um objeto representa
	 * uma data no formato USA yyyy-mm-dd
	 * @param string $data
	 */

	public static function representaUmaDataUSA($data)
	{
		$ano  = substr($data, 0,4);
		$sep1 = substr($data, 4,1);
		$mes  = substr($data, 5,2);
		$sep2 = substr($data, 7,1);
		$dia  = substr($data, 8,2);
		if(($sep1=='-')&&($sep2=='-')&&(checkdate($mes, $dia, $ano)))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 *
	 * Funcao que verifica se um informa��o armazenada em um objeto representa
	 * uma data no formato Br dd/mm/yyyy
	 * @param string $data
	 */

	public static function representaUmaDataBr($data)
	{
		$dia  = substr($data, 0,2);
		$sep1 = substr($data, 2,1);
		$mes  = substr($data, 3,2);
		$sep2 = substr($data, 5,1);
		$ano  = substr($data, 6,4);
		if(($sep1=='/')&&($sep2=='/')&&(checkdate($mes, $dia, $ano)))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 *
	 * Funcao para criptografar a senha
	 * @param string $pass
	 */

	public static function passwordMD5($pass)
	{
		return md5($pass);
	}


	/**
	 * Função utilizada para converter um texto para
	 * codificação UTF8.
	 * Enter description here ...
	 * @param $texto
	 */
	public static function uft8($texto)
	{
		return iconv('UTF-8', 'ISO-8859-5', $texto);
	}
	/**
	 *
	 * Funcao para validar email
	 * @param string $email
	 */

	public static function validaEmail($mail)
	{
		if(preg_match("/^([[:alnum:]_.-]){3,}@([[:lower:][:digit:]_.-]{3,})(.[[:lower:]]{2,3})(.[[:lower:]]{2})?$/", $mail))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	 * Função para obter caminho do arquivo
	 *
	 *
	 */

	public static function getPathFile($file)
	{
		$pastas = array(
					'app.control/usuario',
					'app.control/protocolo',
		            'app.control/cehab',
					'app.control',
	   				'app.widgets',
	   				'app.ado', 
	   				'app.model',
	   				'app.config');
		foreach ($pastas as $pasta)
		{
			if (file_exists("{$pasta}/{$file}.class.php"))
			{
				return "{$pasta}";
			}
		}

	}
	
	/**
	 * 
	 * Função para truncar um texto com uma determinada quantidade de caracteres.
	 * Utilzada normalmente nos relatorios pdf para evitar encavalamento de texto. 
	 * @param string $var
	 * @param string $limite
	 * @return mixed
	 */
	public static function resume($var, $limite){
		
		if (strlen($var) > $limite){
			return substr_replace ($var, '...', $limite);
		}
		else{
			
			return substr_replace ($var, '', $limite);
		}
	}
	/**
	 * 
	 * @param String $tempo 00:00:00
	 * @return number
	 */
	public static function strToTime($tempo)
	{
	   $tempo = explode(":", $tempo);
	   $hora  = $tempo[0];
	   $min   = $tempo[1];
	   $seg   = $tempo[2];
	   
	   return ($hora * 3600) + ($min * 60) + $seg;
	
	}
	/**
	 * 
	 * @param number $number
	 */
	public static function timeToStr($number)
	{
	    $hora = (int)($number/(60*60));
	    $min = (int)($number/(60));
	    $min = $min%60;
	    $seg = $number%60;
	    
	    if($hora < 10){
	        $hora = "0" . $hora;
	    }
	    
	    if($min < 10){
	        $min = "0" . $min;
	    }
	    
	    if($seg < 10){
	        $seg = "0" . $seg;
	    }
	    
	    return $hora . ':' . $min . ':' . $seg;
	}
}
?>