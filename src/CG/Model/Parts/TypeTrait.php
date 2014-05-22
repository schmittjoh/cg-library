<?php

namespace CG\Model\Parts;

trait TypeTrait {
	
	private $type;
	private $typeDescription;

	/**
	 * @param string $type
	 */
	public function setType($type, $description = '')
	{
		$this->type = $type;
		$this->setTypeDescription($description);
	
		return $this;
	}
	
	protected function setTypeDescription($description) {
		$this->typeDescription = $description;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getTypeDescription() {
		return $this->typeDescription;
	}
}