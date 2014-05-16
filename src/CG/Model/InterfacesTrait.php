<?php

namespace CG\Model;

trait InterfacesTrait {
	
	private $interfaces = [];
	
	abstract public function addUseStatement($qualifiedName, $alias = null);
	
	abstract public function removeUseStatement($qualifiedName);
	
	abstract public function getNamespace();
	
	/**
	 * Adds an interface. If the interface is passed as PhpInterface object,
	 * the interface is also added as use statement.
	 * 
	 * @param PhpInterface|string $interface interface or qualified name
	 * @return $this
	 */
	public function addInterface($interface)
	{
		if ($interface instanceof PhpInterface) {
			$name = $interface->getName();
			$qname = $interface->getQualifiedName();
			$namespace = $interface->getNamespace();
			
			if ($namespace != $this->getNamespace()) {
				$this->addUseStatement($qname);
			}
		} else {
			$name = $interface;
		}
		
		if (!in_array($name, $this->interfaces)) {
			$this->interfaces[] = $name;
		}

		return $this;
	}
	
	public function getInterfaces()
	{
		return $this->interfaces;
	}
	
	public function hasInterfaces() {
		return count($this->interfaces) > 0;
	}
	
	/**
	 * Removes an interface. If the interface is passed as PhpInterface object,
	 * the interface is also remove from the use statements.
	 * 
	 * @param PhpInterface|string $interface interface or qualified name
	 * @return $this
	 */
	public function removeInterface($interface)
	{
		if ($interface instanceof PhpInterface) {
			$name = $interface->getName();
			$qname = $interface->getQualifiedName();

			$this->removeUseStatement($qname);
		} else {
			$name = $interface;
		}
		
		$index = array_search($name, $this->interfaces);
		if ($index) {
			unset($this->interfaces[$name]);
		}

		return $this;
	}

	/**
	 * 
	 * @param array $interfaces
	 * @return $this
	 */
	public function setInterfaces(array $interfaces)
	{
		foreach ($interfaces as $interface) {
			$this->addInterface($interface);
		}
	
		return $this;
	}
}