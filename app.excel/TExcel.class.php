<?php
abstract class TExcel extends PHPExcel{

	public function __construct(){
		parent::__construct();		
	}

	public abstract function setContent($dataObject);

	public function download($fileName = 'planilha.xls'){
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');
		// Se for o IE9, isso talvez seja necessário
		header('Cache-Control: max-age=1');

		// Acessamos o 'Writer' para poder salvar o arquivo
		$objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel5');

		// Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
		$objWriter->save('php://output');
	}

}
?>