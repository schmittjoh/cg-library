<?php

namespace CG\Model;

class PhpInterface extends AbstractPhpStruct implements GenerateableInterface
{
	use InterfacesTrait;
	use ConstantsTrait;
	
	public function __construct($name = null)
	{
		parent::__construct($name);
	}
	
	/* (non-PHPdoc)
	 * @see \CG\Model\AbstractModel::generateDocblock()
	 */
	public function generateDocblock() {
		$docblock = parent::generateDocblock();
		
		foreach ($this->constants as $constant) {
			$constant->generateDocblock();
		}
		
		$this->setDocblock($docblock);
		
		return $docblock;
	}
}