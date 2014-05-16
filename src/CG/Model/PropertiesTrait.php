<?php

namespace CG\Model;

trait PropertiesTrait {
	
	/**
	 * @var PhpProperty[]
	 */
	private $properties = [];
	
	/**
	 * 
	 * @param PhpProperty[] $properties
	 * @return $this
	 */
	public function setProperties(array $properties)
	{
		foreach ($this->properties as $prop) {
			$prop->setParent(null);
		}
		
		$this->properties = [];
		
		foreach ($properties as $prop) {
			$this->setProperty($prop);
		}
		
	
		return $this;
	}
	
	public function setProperty(PhpProperty $property)
	{
		$property->setParent($this);
		$this->properties[$property->getName()] = $property;
	
		return $this;
	}
	
	/**
	 * @param string $property
	 */
	public function hasProperty($property)
	{
		if ($property instanceof PhpProperty) {
			$property = $property->getName();
		}
	
		return isset($this->properties[$property]);
	}

	/**
	 * @param string $property
	 */
	public function removeProperty($property)
	{
		if ($property instanceof PhpProperty) {
			$property = $property->getName();
		}
	
		if (!array_key_exists($property, $this->properties)) {
			throw new \InvalidArgumentException(sprintf('The property "%s" does not exist.', $property));
		}
		$p = $this->properties[$property];
		$p->setParent(null);
		unset($this->properties[$property]);
	
		return $this;
	}
	
	public function getProperties()
	{
		return $this->properties;
	}
}