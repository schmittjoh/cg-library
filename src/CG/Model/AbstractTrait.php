<?php
namespace CG\Model;

trait AbstractTrait {

	private $abstract = false;
	
	public function isAbstract()
	{
		return $this->abstract;
	}
	
	/**
	 * @param boolean $bool
	 */
	public function setAbstract($bool)
	{
		$this->abstract = (Boolean) $bool;
	
		return $this;
	}
}