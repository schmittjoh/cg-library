<?php

namespace CG\Model\Parts;

use CG\Model\PhpTrait;

trait TraitsTrait {
	
	private $traits = [];
	
	abstract public function addUseStatement($qualifiedName, $alias = null);
	
	abstract public function removeUseStatement($qualifiedName);
	
	abstract public function getNamespace();
	
	/**
	 * Adds a trait. If the trait is passed as PhpTrait object,
	 * the trait is also added as use statement.
	 *
	 * @param PhpTrait|string $trait trait or qualified name
	 */
	public function addTrait($trait)
	{
		if ($trait instanceof PhpTrait) {
			$name = $trait->getName();
			$qname = $trait->getQualifiedName();
			$namespace = $trait->getNamespace();
				
			if ($namespace != $this->getNamespace()) {
				$this->addUseStatement($qname);
			}
		} else {
			$name = $trait;
		}
		
		if (!in_array($name, $this->traits)) {
			$this->traits[] = $name;
		}

		return $this;
	}
	
	public function getTraits()
	{
		return $this->traits;
	}
	

	/**
	 *
	 * @param PhpTrait|string $interface
	 * @return boolean
	 */
	public function hasTrait($interface) {
		if (!$interface instanceof PhpTrait) {
			$interface = new PhpTrait($interface);
		}
		$name = $interface->getName();
		return in_array($name, $this->traits);
	}
	
	/**
	 * Adds a trait. If the trait is passed as PhpTrait object,
	 * the trait is also removed from use statements.
	 *
	 * @param PhpTrait|string $trait trait or qualified name
	 */
	public function removeTrait($trait)
	{
		if ($trait instanceof PhpTrait) {
			$name = $trait->getName();
		} else {
			$name = $trait;
		}

		$index = array_search($name, $this->traits);
		if ($index) {
			unset($this->traits[$name]);
			
			if ($trait instanceof PhpTrait) {
				$qname = $trait->getQualifiedName();
				$this->removeUseStatement($qname);
			}
		}

		return $this;
	}

	public function setTraits(array $traits)
	{
		foreach ($traits as $trait) {
			$this->addTrait($trait);
		}
	
		return $this;
	}
}