<?php

namespace CG\Model;

class PhpTrait extends AbstractPhpStruct implements GenerateableInterface
{
	use PropertiesTrait;
	use TraitsTrait;

	public function __construct($name = null)
	{
		parent::__construct($name);
	}
	
	/* (non-PHPdoc)
	 * @see \CG\Model\AbstractModel::generateDocblock()
	*/
	public function generateDocblock() {
		$docblock = parent::generateDocblock();

		foreach ($this->properties as $prop) {
			$prop->generateDocblock();
		}
		
		$this->setDocblock($docblock);
	
		return $docblock;
	}
}