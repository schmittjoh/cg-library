<?php

namespace CG\Model\Parts;

trait DefaultValueTrait {
	
	private $defaultValue;
	private $hasDefaultValue = false;
	
	public function setDefaultValue($value)
	{
		$this->defaultValue = $value;
		$this->hasDefaultValue = true;
	
		return $this;
	}
	
	public function unsetDefaultValue()
	{
		$this->defaultValue = null;
		$this->hasDefaultValue = false;
	
		return $this;
	}
	
	public function getDefaultValue()
	{
		return $this->defaultValue;
	}
	
	public function hasDefaultValue()
	{
		return $this->hasDefaultValue;
	}
}