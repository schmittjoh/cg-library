<?php

namespace CG\Model;

trait PropertiesTrait {
	
	/**
	 * @var PhpProperty[]
	 */
	private $properties = [];
	
	public function setProperties(array $properties)
	{
		$this->properties = $properties;
	
		return $this;
	}
	
	public function setProperty(PhpProperty $property)
	{
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
		unset($this->properties[$property]);
	
		return $this;
	}
	
	public function getProperties()
	{
		return $this->properties;
	}
}