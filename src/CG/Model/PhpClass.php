<?php
namespace CG\Model;

class PhpClass extends AbstractPhpStruct implements GenerateableInterface, TraitsInterface {

	use InterfacesTrait;
	use AbstractTrait;
	use FinalTrait;
	use ConstantsTrait;
	use PropertiesTrait;
	use TraitsTrait;
	
	private $parentClassName;
	
	public function __construct($name = null)
	{
		parent::__construct($name);
	}

	public function getParentClassName()
	{
		return $this->parentClassName;
	}
	
	/**
	 * @param string|null $name
	 */
	public function setParentClassName($name)
	{
		$this->parentClassName = $name;
	
		return $this;
	}

	/* (non-PHPdoc)
	 * @see \CG\Model\AbstractModel::generateDocblock()
	*/
	public function generateDocblock() {
		$docblock = parent::generateDocblock();
	
		foreach ($this->constants as $constant) {
			$constant->generateDocblock();
		}
		
		foreach ($this->properties as $prop) {
			$prop->generateDocblock();
		}
		
		$this->setDocblock($docblock);
	
		return $docblock;
	}
}