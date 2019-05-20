<?php
class TDataGridActionExtra extends TDataGridAction{

	private $condition;
	
	public function setCondition($condition)
	{
		$this->condition = $condition;
	}

	public function getCondition($object)
	{
		if($this->condition)
		{
			$nameCol = $this->condition[0];
			$valueCol = $this->condition[1];
			$data = $object->$nameCol;
			return ($data == $valueCol);
		}
		else
		{
			return TRUE;
		}
		 
		 
	}

}