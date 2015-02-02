<?php
namespace CG\Model\Parts;

trait NameTrait {

	private $name;

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
	
		return $this;
	}

	public function getName()
	{
		return $this->name;
	}	
}