<?php

namespace CG\Model;

use CG\Model\Parts\InterfacesTrait;
use CG\Model\Parts\ConstantsTrait;
use CG\Utils\ReflectionUtils;

class PhpInterface extends AbstractPhpStruct implements GenerateableInterface
{
	use InterfacesTrait;
	use ConstantsTrait;
	
	public static function fromReflection(\ReflectionClass $ref)
	{
		$interface = new static();
		$interface
			->setQualifiedName($ref->name)
			->setAbstract($ref->isAbstract())
			->setFinal($ref->isFinal())
			->setConstants($ref->getConstants())
		;
	
		$interface->setUseStatements(self::$phpParser->parseClass($ref));
	
	
        if ($doc = $ref->getDocComment()) {
	        $docblock = new Docblock(ReflectionUtils::getUnindentedDocComment($doc));
	        $interface->setDocblock($docblock);
	        $interface->setDescription($docblock->getShortDescription());
	        $interface->setLongDescription($docblock->getLongDescription());
        }
	
		foreach ($ref->getMethods() as $method) {
			$interface->setMethod(static::createMethod($method));
		}

		return $interface;
	}
	
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