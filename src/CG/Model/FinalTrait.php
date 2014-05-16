<?php
namespace CG\Model;

trait FinalTrait {
	
	private $final = false;
	

	public function isFinal()
	{
		return $this->final;
	}
	

	/**
	 * @param boolean $bool
	 * @return $this
	 */
	public function setFinal($bool)
	{
		$this->final = (Boolean) $bool;
	
		return $this;
	}
}