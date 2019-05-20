<?php
/**
 * classe TNavegator
 * classe para construção de um navegador de paginas de dados.
 */
class TPaginator extends TTable
{
	
	private $class;
	private $countPag;
	private $countRecord;
	private $pagNow;
	private $filter;
	private $order;
	private $urlFirst;
	private $urlPrior;
	private $urlNext;
	private $urlLast;
	private $images;
	private $table;
	private $fieldsOrder;
	
	public function __construct()
    {
		parent::__construct();
	}
	
	public function setUrl()
	{
		$action_first = new TAction(array($this->class, 'onReload'));
		$action_first->setParameter('pag', 1);
	
		$action_prior = new TAction(array($this->class, 'onReload'));
		$action_prior->setParameter('pag', (($this->pagNow-1) <= 0)?1:($this->pagNow-1));
		

		$action_next = new TAction(array($this->class, 'onReload'));
		$action_next->setParameter('pag', (($this->pagNow+1) > ($this->countPag))?$this->countPag:($this->pagNow+1));

		$action_last = new TAction(array($this->class, 'onReload'));
		$action_last->setParameter('pag', $this->countPag);

		if($this->filter)
		{
			$action_first->setParameter('param', $this->filter);
			$action_prior->setParameter('param', $this->filter);
			$action_next->setParameter('param', $this->filter);
			$action_last->setParameter('param', $this->filter);			
		}
		

		if($this->order)
		{
			$action_first->setParameter('order', $this->order);
			$action_prior->setParameter('order', $this->order);
			$action_next->setParameter('order', $this->order);
			$action_last->setParameter('order', $this->order);			
		}

		$this->urlFirst=$action_first->serialize();
		$this->urlPrior=$action_prior->serialize();
		$this->urlNext=$action_next->serialize();
		$this->urlLast=$action_last->serialize();
		
    }
	
	public function createNavegator()
	{
		$this->width='100%';
		$row_navegator = parent::addRow();
		$link_first = new TElement('a');
		$link_first->href=$this->urlFirst;
		$link_first->add(new TImage($this->images[0]));
		$row_navegator->addCell($link_first);

		$link_prior = new TElement('a');
		$link_prior->href=$this->urlPrior;
		$link_prior->add(new TImage($this->images[1]));
		$row_navegator->addCell($link_prior);

		$link_next = new TElement('a');
		$link_next->href=$this->urlNext;
		$link_next->add(new TImage($this->images[2]));
		$row_navegator->addCell($link_next);

		$link_last = new TElement('a');
		$link_last->href=$this->urlLast;
		$link_last->add(new TImage($this->images[3]));
		$row_navegator->addCell($link_last);
		
		/* COMBOBOX DE PAGINAÇÃO */
		$comboPag = new TCombo('comboPag');
		$comboPag->addClass('sizefiled_100');
		$comboPag->setProperty('id','comboPag');
		for($i=1;$i<$this->countPag+1;$i++)
		{
			$actionPag = new TAction(array($this->class, 'onReload'));
			$actionPag->setParameter('pag', $i);
			if($this->filter)
			{	
				$actionPag->setParameter('param', $this->filter);
			}
			if($this->order)
			{
				$actionPag->setParameter('order', $this->order);
			}
			$arrayPag[$actionPag->serialize()]='Página '.$i;
			
		}
		$comboPag->addItems($arrayPag);
		$keys = array_keys($arrayPag);
		$comboPag->setValue(array_shift($keys));
		//$comboPag->setValue('0');	
		
		$row_navegator->addCell($comboPag);	
		
		/* COMBOBOX DE ORDENAÇÃO */
		$comboOrderBy = new TCombo('comboOrderBy');
		$comboOrderBy->addClass('sizefiled_150');
		$comboOrderBy->setProperty('id','comboOrderBy');
		foreach ($this->fieldsOrder as $chave => $item)
		{
			$actionOrder = new TAction(array($this->class, 'onReload'));
			$actionOrder->setParameter('pag', 1);
			if($this->filter)
			{	
				$actionOrder->setParameter('param', $this->filter);
			}
			$actionOrder->setParameter('order', $chave);
			$arrayOrder[$actionOrder->serialize()]=$item;
		
		}
		$comboOrderBy->addItems($arrayOrder);		
		//$comboOrderBy->setValue(array_shift(array_keys($arrayOrder)));
		$row_navegator->addCell(new TLabel('Ordenar por: '));
		$row_navegator->addCell($comboOrderBy);	
		$row_navegator->addCell(new TLabel('Pagina: '.$this->pagNow."/".$this->countPag));	
		$cell = $row_navegator->addCell(new TLabel('Total de Registros: '.$this->countRecord));	
		$cell->align = 'right';
	}
	

	function clear()
    {
        $copy = $this->children[0];
        $this->children = array();
        $this->children[] = $copy;
        //$this->rowcount = 0;
    }

	function setCountPag($countPag)
	{
		$this->countPag = $countPag;
	}

	function setClass($class)
	{
		$this->class = $class;
	}

	function setPagNow($pagNow)
	{
		$this->pagNow = $pagNow;
	}

	function setCountRecord($countRecord)
	{
		$this->countRecord = $countRecord;
	}

	function setFilter($filter)
	{
		$this->filter = $filter;
	}

	function setOrder($order)
	{
		$this->order = $order;
	}

	function setImages($images)
	{
		$this->images = $images;
	}
	
	function setFieldsOrder($fieldsOrder)
	{
		$this->fieldsOrder = $fieldsOrder;
	}

}
?>